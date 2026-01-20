<?php

namespace App\Exports;

use App\Models\PPDBUser;
use App\Models\Product;
use App\Models\Student;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Vendor;
use Auth;

class VoucherNewUsageExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $collections = null;

    public function __construct($params)
    {


        $vouchers = Voucher::where('usage_type', 'per_user')
            ->withCount('usages')
            ->with('usages', 'usages.orders');

        //super dirty
        if (Auth::user()->role_units && count(Auth::user()->role_units)) {
            $vouchers = $vouchers->where(function ($q) {
                $q->where('unit_id', NULL);
                foreach (Auth::user()->role_units as $unit) {
                    $q->orWhere('unit_id', 'like', '%"' . $unit . '"%');
                }
            });

            $vouchers = $vouchers->where(function ($q) {
                $q->where('user_id', NULL);
                foreach (PPDBUser::whereIn('unit_id', Auth::user()->role_units)->select('user_id')->get() as $user) {
                    $q->orWhere('user_id', 'like', '%"' . $user->user_id . '"%');
                }
            });
        }

        $vouchers = $vouchers->get();

        $collect = collect();
        $unitUsers = collect();
        $allUsers = collect();

        $vouchers->each(function ($voucher) use ($collect, $unitUsers, $allUsers) {
            $userIds = [];
            if ($voucher->user_id) {
                $userIds = $voucher->user_id;
            } else if ($voucher->unit_id) {
                foreach ($voucher->unit_id as $unitId) {
                    if (!$unitUsers->get($unitId)) {
                        $userIds = PPDBUser::notAccepted()->where('unit_id', $unitId)->pluck('user_id')->toArray();
                        $unitUsers->put($unitId, ['unit_id' => $unitId, 'user_ids' => $userIds]);
                    } else {
                        $userIds = $unitUsers->get($unitId)['user_ids'];
                    }
                }
            }

            if (!$voucher->user_id && !$voucher->unit_id) {
                if ($allUsers->isEmpty()) {
                    $userIds = User::whereHas('ppdb', function ($query) {
                        return $query->notAccepted();
                    })->orWhereHas('student')->pluck('id')->toArray();
                    $allUsers->push($userIds);
                } else {
                    $userIds = $allUsers->get();
                }
            }

            foreach ($userIds as $user_id) {
                $usage_remaining = 0;

                $total_used = $voucher->usages->filter(function ($usage) use ($user_id) {
                    return $usage->orders->filter(function ($order) use ($user_id) {
                        return $user_id == $order->user_id && $order->status !== 'cancel';
                    })->first() ? true : false;
                })->count();

                $lastUsed = $voucher->usages->filter(function ($usage) use ($user_id) {
                    return $usage->orders->filter(function ($order) use ($user_id) {
                        return $user_id == $order->user_id && $order->status !== 'cancel';
                    });
                })->pluck('updated_at')->last();

                if (!is_null($lastUsed) && !empty($lastUsed)) {
//                    $lastDateUsed = date('Ymd', strtotime($lastUsed));
                    $lastDateUsed = date('d-m-Y H:i:s', strtotime($lastUsed));
                } else {
                    $lastDateUsed = null;
                }


                if ($voucher->usage_type === 'per_user') {
                    $usage_remaining = $voucher->usage_limit - $total_used;
                }

                if ($voucher->usage_type === 'cumulative') {
                    $usage_remaining = $voucher->usage_remaining;
                }

                if ($voucher->type == 'free_product') {
                    $product_free = '';
                    if (!empty($voucher->rule)) {
                        foreach (json_decode($voucher->rule) as $item) {
                            $product = Product::where('id', $item)->first();
                            $product_free .= $product->name . ', ';
                        };
                        $product_free = substr($product_free, 0, -2);
                    }
                    $type = 'Free Product';
                    $free = $product_free;
                }

                if ($voucher->type == 'discount_fixed') {
                    $type = 'Discount Fixed';
                    $free = 'Rp' . number_format($voucher->rule);
                }

                if ($voucher->type == 'discount_percent') {
                    $type = 'Discount Percent';
                    $free = $voucher->rule . '%';
                }

                if ($data = $collect->get($user_id)) {
                    $data['voucher'][$voucher->code] = [
                        'code' => $voucher->code,
                        'type' => Str::title(str_replace('_', ' ', $voucher->type)),
                        'rule' => $voucher->rule,
                        'usage_limit' => $voucher->usage_limit,
                        'active' => $voucher->active,
                        'usage_type' => $voucher->usage_type,
                        'usage_count' => $voucher->usage_count,
                        'total_used' => $total_used,
                        'usage_remaining' => $usage_remaining,
                        'status' => $total_used ? 'claimed' : 'available',
                        'label_color' => $total_used ? 'danger' : 'success',
                        'type_value' => $voucher->type_value,
                        'last_date_used' => $lastDateUsed,
                        'type_voucher' => $type,
                        'free_voucher' => $free
                        // date('m-d-Y', strtotime($lastDateUsed))
                    ];
                    $collect->pull($user_id);
                    $collect->put($user_id, $data);
                } else {
                    $collect->put($user_id, [
                        'user_id' => $user_id,
                        'voucher' => [
                            $voucher->code => [
                                'code' => $voucher->code,
                                'type' => Str::title(str_replace('_', ' ', $voucher->type)),
                                'rule' => $voucher->rule,
                                'usage_limit' => $voucher->usage_limit,
                                'active' => $voucher->active,
                                'usage_type' => $voucher->usage_type,
                                'usage_count' => $voucher->usage_count,
                                'total_used' => $total_used,
                                'usage_remaining' => $usage_remaining,
                                'status' => $total_used ? 'claimed' : 'available',
                                'label_color' => $total_used ? 'danger' : 'success',
                                'type_value' => $voucher->type_value,
                                'last_date_used' => $lastDateUsed,
                                'type_voucher' => $type,
                                'free_voucher' => $free
                            ]
                        ]
                    ]);
                }
            }
        });

        $ppdb_users = PPDBUser::select(['id', 'user_id', 'name', 'unit_id', 'register_number', 'school_year'])
            ->with('unit')
            ->whereIn('user_id', $collect->keys())
            ->get()->keyBy('user_id')
            ->each(function ($ppdb_user) use ($collect) {
                if ($data = $collect->get($ppdb_user->user_id)) {
                    $data['name'] = $ppdb_user->name;
                    $data['register_number'] = $ppdb_user->register_number;
                    $data['school_year'] = $ppdb_user->school_year;
                    $data['unit_id'] = $ppdb_user->unit->id;
                    $data['unit'] = $ppdb_user->unit->name;

                    $collect->pull($ppdb_user->user_id);
                    $collect->put($ppdb_user->user_id, $data);
                }
            });

        $students = Student::select(['id', 'user_id', 'name', 'nis', 'school_year'])
            ->with('class', 'class.unit')
            ->whereIn('user_id', $collect->diffKeys($ppdb_users)->keys())
            ->get()->keyBy('user_id')
            ->each(function ($student) use ($collect) {
                if ($data = $collect->get($student->user_id)) {
                    $data['nis'] = $student->nis;
                    $data['name'] = $student->name;
                    $data['school_year'] = $student->school_year;
                    $data['unit_id'] = $student->class->unit->id ?? null;
                    $data['unit'] = $student->class->unit->name ?? null;
                    $collect->pull($student->user_id);
                    $collect->put($student->user_id, $data);
                }
            });

        if (isset($params['status']) && $params['status']) {
            $collect = $collect->map(function ($data) use ($params) {
                $data['voucher'] = array_filter($data['voucher'], function ($value) use ($params) {
                    return $value['status'] == $params['status'];
                });
                return $data;
            })->filter(function ($coll) {
                return count($coll['voucher']);
            });
        }

        if (isset($params['date_range']) && $params['date_range']) {
            $collect = $collect->map(function ($data) use ($params) {
                $data['voucher'] = array_filter($data['voucher'], function ($value) use ($params) {
                    $dateStart = date('Ymd', strtotime(Carbon::parse(trim(explode('-', $params['date_range'])[0]))));
                    $dateEnd = date('Ymd', strtotime(Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay()));
                    return $value['last_date_used'] >= $dateStart && $value['last_date_used'] <= $dateEnd;
                });
                return $data;
            })->filter(function ($coll) {
                return count($coll['voucher']);
            });
        }

        if (isset($params['name']) && $params['name']) {
            $collect = $collect->filter(function ($data) use ($params) {
                if (isset($data['name'])) {
                    return Str::contains(strtolower($data['name']), strtolower($params['name']));
                }
            });
        }

        if (isset($params['unit']) && $params['unit']) {
            $collect = $collect->filter(function ($data) use ($params) {
                if (isset($data['unit_id'])) {
                    # code...
                    return $data['unit_id'] == $params['unit'];
                }
            });
        }

        if (isset($params['year']) && $params['year']) {
            $collect = $collect->filter(function ($data) use ($params) {
                if (isset($data['school_year'])) {
                    # code...
                    return Str::contains(strtolower($data['school_year']), strtolower($params['year']));
                }
            });
        }

        $datas = $collect->sortBy('name');

        $this->collections = $datas;

        return $this->collections;

    }

    public function collection()
    {
        return $this->collections;
    }

    public function setTemplate(bool $value)
    {

        $this->isTemplate = $value;
    }

    public function map($data): array
    {
        foreach ($data['voucher'] as $voucher) {
            return [
                isset($data['unit']) ? $data['unit'] : '-',
                isset($data['name']) ? $data['name'] : '-',
                $voucher['code'],
                $voucher['type'],
                $voucher['free_voucher'],
                $voucher['usage_remaining'],
                $voucher['last_date_used'],
                $voucher['status'],
            ];
        }
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
//            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'UNIT',
            'NAMA SISWA',
            'CODE',
            'TYPE VOUCHER',
            'VOUCHER',
            'LIMIT',
            'KLAIM',
            'STATUS',
        ];
    }
}
