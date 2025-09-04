<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\ProductOrder;
use App\Models\Voucher;
use App\Models\Product;
use App\Models\PPDBUser;
use App\Models\Student;
use App\Models\Unit;
use App\Models\User;
use App\Lib\DbTrx;
use Carbon\Carbon;
use Auth;
use Request;
use Illuminate\Support\Str;

class VoucherService
{
    /**
     * Filter voucher using parameter (array)
     * @param mixed $params
     * @param int|null $paginate_limit
     *
     * @return [type]
     */
    public function filter($params, int $paginate_limit = null, array $related = null)
    {
        $vouchers = Voucher::query();
        // Filter for voucher code
        if (array_key_exists('code', $params) && $params['code']) {
            $vouchers->where('code', 'like', '%' . $params['code'] .'%');
        }
        // Filter for username by checking user_id attribute
        if (array_key_exists('name', $params) && $params['name']) {
            $users = User::whereHas('ppdb', function($query) use($params) {
                $query->where('name', 'like', "%$params[name]%");
            })->orWhereHas('student', function($query) use($params) {
                $query->where('name', 'like', "%$params[name]%");
            })->get();
            foreach ($users as $user) {
                $vouchers->orWhere('user_id', 'like', '%' . $user->id . '%');
            }
        }
        // Filter for voucher type
        if (array_key_exists('type', $params) && $params['type']) {
            $vouchers->where('type', $params['type']);
        }
        // Filter for unit by checking unit_id attribute
        if (array_key_exists('unit', $params) && $params['unit']) {
            $vouchers->where('unit_id', 'like', '%' . $params['unit'] . '%');
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $vouchers->where('year', $params['year']);
        }
        // Eager load relations
        if ($related) {
            $vouchers->with($related);
        }
        $vouchers->orderBy('updated_at', 'desc');
        // Paginate if limit set
        if ($paginate_limit) {
            return $vouchers->paginate($paginate_limit);
        } else {
            return $vouchers->get();
        }
    }

    public function getAvailableYears()
    {
        return Voucher::distinct()->whereNotNull('year')->get('year as year');
    }

    public function generateAddingData($nav)
    {
        return [
            'unitOption' => Unit::byUserRole()->select('name', 'id')->get()->pluck('name', 'id'),
            'userOption' => $this->userOption(),
            'productOption' => Product::select('name', 'id')->get()->pluck('name', 'id'),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $voucher = Voucher::where('id', $id)->firstOrFail();

        return [
            'unitOption' => Unit::byUserRole()->select('name', 'id')->get()->pluck('name', 'id'),
            'userOption' => $this->userOption(),
            'productOption' => Product::select('name', 'id')->get()->pluck('name', 'id'),
            'status' => 'edit',
            'voucher' => $voucher,
            'nav' => $nav
        ];
    }

    public function create($input)
    {
        DbTrx::useTrx(function() use ($input) {
            $voucher = Voucher::create($input);
            return $voucher;
        });
    }

    public function update($id, $input)
    {
        $voucher = Voucher::where('id', $id)->firstOrFail();

        DbTrx::useTrx(function() use ($input, $voucher) {
            return $voucher->update($input);
        });
    }

    private function userOption()
    {
        return Cache::remember('user_options_'. Auth::user()->id, 600, function() {
            $collections = collect();
            $users = User::whereIn('type', ['siswa', 'ppdb'])->select('id')->with(['ppdb' => function($with) {
                return $with->select('name', 'user_id', 'register_number', 'unit_id');
            }, 'student' => function($with) {
                return $with->select('name', 'user_id', 'nis');
            }])->get();
            foreach ($users as $user) {
                if (!$user['ppdb'] && !$user['student']) {
                    continue;
                }
                if ($user['ppdb'] && Auth::user()->role_units && count(Auth::user()->role_units)) {
                    if (!in_array($user['ppdb']['unit_id'], Auth::user()->role_units)) {
                        continue;
                    }
                }

                $collections->put($user->id, $user['ppdb'] ? '['.$user['ppdb']['register_number']. '] ' . $user['ppdb']['name'] : '['. $user['student']['nis'] .'] '. $user['student']['name']);
            }

            return $collections->sort();
        });
    }

    public function generateCode()
    {
        $code = strtoupper(Str::random(8));
        while (Voucher::where('code', $code)->first()) {
            $code = strtoupper(Str::random(8));
        }

        return $code;
    }

    public function generateFreeVouchersForOlahRagaProduct(PPDBUser $ppdb, $useRandomCode = true)
    {
        $gender = $ppdb->gender;
        $products = Product::published()->whereHas('units', function($query) use ($ppdb) {
            return $query->where('unit_id', $ppdb->unit_id);
        })->where(function($query) use ($gender) {
            $gender = $gender == 'female' ? 'Putri' : 'Putra';
            return $query->where('name', 'like', '%Kaos Olah Raga%')
                ->orWhere('name', 'like', '%Celana Olah Raga '. $gender .'%');
        })->select('id')->pluck('id')->all();

        $code = "";
        $unit = 0;
        $periode = 0;
        if ($ppdb->unit_id) {
            $unit = (int) $ppdb->unit_id;
        }
        if ($ppdb->register_number) {
            $periode = (int) substr($ppdb->register_number, 0, 2);
        }

        if ($useRandomCode) {
            $code = $this->generateCode();
        } else {
            $code = $code . sprintf("%02d%02d%02d", $unit, $periode, ($periode+1));
            if ($gender && $gender === 'female') {
                $code = $code . 'PI';
            } else {
                $code  = $code . 'PA';
            }
        }

        $voucher = Voucher::where('code', $code)->first();
        if ($voucher) {
            $voucher->user_id = array_merge($voucher->user_id, [$ppdb->user_id]);
            $voucher->save();
        } else {
            $voucher = new Voucher();
            $voucher->code = $code;
            $voucher->type = 'free_product';
            $voucher->rule = json_encode($products);
            $voucher->user_id = [$ppdb->user_id];
            $voucher->usage_type = Voucher::USAGE_PER_USER;
            $voucher->usage_limit = 1;
            $voucher->active = 1;
            $voucher->save();
        }
        $voucher->refresh();

        return $voucher;
    }

    public function removeGeneratedFreeVouchersForOlahRagaProduct(PPDBUser $ppdb)
    {
        $gender = $ppdb->gender;
        $products = Product::published()->whereHas('units', function ($query) use ($ppdb) {
            return $query->where('unit_id', $ppdb->unit_id);
        })->where(function ($query) use ($gender) {
            $gender = $gender == 'female' ? 'Putri' : 'Putra';
            return $query->where('name', 'like', '%Kaos Olah Raga%')
            ->orWhere('name', 'like', '%Celana Olah Raga ' . $gender . '%');
        })->select('id')->pluck('id')->all();

        $code = "";
        $unit = 0;
        $periode = 0;
        if ($ppdb->unit_id) {
            $unit = (int) $ppdb->unit_id;
        }
        if ($ppdb->register_number) {
            $periode = (int) substr($ppdb->register_number, 0, 2);
        }

        $code = $code . sprintf("%02d%02d%02d", $unit, $periode, ($periode + 1));
        if ($gender && $gender === 'female') {
            $code = $code . 'PI';
        } else {
            $code  = $code . 'PA';
        }

        $voucher = Voucher::where('code', $code)->first();
        if ($voucher) {
            $index =  array_search($ppdb->user_id, $voucher->user_id);
            $user_ids = $voucher->user_id;
            unset($user_ids[$index]);
            $voucher->user_id = $user_ids;
            print_r($voucher->user_id);
            $voucher->save();
            $voucher->refresh();
        }
    }

    public function generateVoucherClaimsData($nav, $params)
    {
        $vouchers = Voucher::where('usage_type', 'per_user')
                        ->withCount('usages')
                        ->with('usages','usages.orders');

        //super dirty
        if (Auth::user()->role_units && count(Auth::user()->role_units)) {
            $vouchers = $vouchers->where(function($q) {
                $q->where('unit_id', NULL);
                foreach (Auth::user()->role_units as $unit) {
                    $q->orWhere('unit_id', 'like', '%"'. $unit .'"%');
                }
            });

            $vouchers = $vouchers->where(function($q) {
                $q->where('user_id', NULL);
                foreach (PPDBUser::whereIn('unit_id', Auth::user()->role_units)->select('user_id')->get() as $user) {
                    $q->orWhere('user_id', 'like', '%"'. $user->user_id .'"%');
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
                    if (! $unitUsers->get($unitId)) {
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

                $total_used = $voucher->usages->filter(function($usage) use ($user_id) {
                    return $usage->orders->filter(function ($order) use ($user_id) {
                        return $user_id == $order->user_id && $order->status !== 'cancel';
                    })->first() ? true : false ;
                })->count();

                $lastUsed =  $voucher->usages->filter(function($usage) use ($user_id) {
                    return $usage->orders->filter(function ($order) use ($user_id) {
                        return $user_id == $order->user_id && $order->status !== 'cancel';
                    });
                })->pluck('updated_at')->last();

                if (!is_null($lastUsed) && !empty($lastUsed)) {
                    $lastDateUsed = date('Ymd', strtotime($lastUsed));
                } else {
                    $lastDateUsed = null;
                }


                if ($voucher->usage_type === 'per_user') {
                    $usage_remaining = $voucher->usage_limit - $total_used;
                }

                if ($voucher->usage_type === 'cumulative') {
                    $usage_remaining = $voucher->usage_remaining;
                }

                if ($data = $collect->get($user_id)) {
                    $data['voucher'][$voucher->code] = [
                        'code' => $voucher->code,
                        'type' => Str::title(str_replace('_',' ',$voucher->type)),
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
                        'last_date_used' => $lastDateUsed
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
                                'type' => Str::title(str_replace('_',' ',$voucher->type)),
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
                                'last_date_used' => $lastDateUsed
                            ]
                        ]
                    ]);
                }
            }
        });

        $ppdb_users = PPDBUser::select(['id', 'user_id', 'name', 'unit_id', 'register_number','school_year'])
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
                $data['voucher'] = array_filter($data['voucher'], function($value) use ($params) {
                    return $value['status'] == $params['status'];
                });
                return $data;
            })->filter(function ($coll) {
                return count($coll['voucher']);
            });
        }

        if (isset($params['date_range']) && $params['date_range']) {
            $collect = $collect->map(function ($data) use ($params) {
                $data['voucher'] = array_filter($data['voucher'], function($value) use ($params) {
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

        return [
            'nav' => $nav,
            'units' => Unit::byUserRole()->get(),
            'years' => $this->getAvailableYears(),
            'params' => $params,
            'datas' => $collect->sortBy('name')
        ];
    }

    public function filterUsageMiss(array $params, int $paginate_limit = null, array $related = null)
    {
        $productOrders = ProductOrder::query();
        if (array_key_exists('search', $params) && array_key_exists('scope', $params) && $params['search']) {
            switch ($params['scope']) {
                case 'student_name':
                    $productOrders->whereHas('user.student', function ($query) use ($params) {
                        $query->where('name', 'like', "%$params[search]%");
                    })->orWhereHas('user.ppdb', function ($query) use ($params) {
                        $query->where('name', 'like', "%$params[search]%");
                    });
                    break;
                case 'register_number':
                    $productOrders->whereHas('user.student', function ($query) use ($params) {
                        $query->where('register_number', 'like', "%$params[search]%");
                    })->orWhereHas('user.ppdb', function ($query) use ($params) {
                        $query->where('register_number', 'like', "%$params[search]%");
                    });
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('status', $params) && $params['status']) {
            switch ($params['status']) {
                case 'payment_not_confirmed':
                    $productOrders->paymentNotConfirmed();
                    break;
                case 'payment_uploaded':
                    $productOrders->paymentUploaded();
                    break;
                case 'payment_confirmed':
                    $productOrders->paymentConfirmed();
                    break;
                case 'cancel':
                    $productOrders->canceled();
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('unit', $params) && $params['unit']) {
            $productOrders->whereHas('user.ppdb', function($query) use($params) {
                $query->where('unit_id', $params['unit']);
            })->orWhereHas('user.student.class', function($query) use($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if ($related) {
            $productOrders->with($related);
        }

        return $productOrders->where('voucher', 'like', '%"type":"free_product"%')
            ->with('productOrderDetails', 'user', 'user.ppdb', 'user.student')
            ->orderBy('invoice_no', 'ASC')->get();
    }
}
