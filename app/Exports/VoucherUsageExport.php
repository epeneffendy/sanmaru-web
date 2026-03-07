<?php

namespace App\Exports;

use App\Models\PPDBUser;
use App\Models\Product;
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

class VoucherUsageExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $collections = null;

    public function __construct($params)
    {

        $vouchers = Voucher::get();

        $usage = [];
        $type = $free = '';
        foreach ($vouchers as $ind => $voucher) {

            if (!empty($voucher->user_id)) {
                foreach ($voucher->user_id as $user) {

                    $user_id = $user;

                    $total_used = $voucher->usages->filter(function ($usage) use ($user_id) {
                        return $usage->orders->filter(function ($order) use ($user_id) {
                            return $user_id == $order->user_id && $order->status !== 'cancel';
                        })->first() ? true : false;
                    })->count();

                    if ($voucher->usage_type === 'per_user') {
                        $usage_remaining = $voucher->usage_limit - $total_used;
                    }

                    if ($voucher->usage_type === 'cumulative') {
                        $usage_remaining = $voucher->usage_remaining;
                    }

                    $ppdb = PPDBUser::where([
                        'user_id' => $user
                    ]);
                    if (isset($params['unit'])) {
                        $ppdb->where(['unit_id' => $params['unit']]);
                    }

                    if (isset($params['name'])) {
                        $ppdb->where('name', 'like', '%' . $params['name'] . '%');
                    }


                    if (isset($params['school_year'])) {
                        $ppdb->where(['school_year' => $params['school_year']]);
                    } else {
                        $year = date('Y') + 1;
                        $ppdb->where(['school_year' => $year]);
                    }

                    $filter_status = false;
                    if (isset($params['status'])) {
                        if ($params['status'] == 'available' || $params['status'] == 'claimed') {
                            $filter_status = true;
                        }
                    }

                    $ppdb = $ppdb->first();


                    if ($ppdb) {
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

                        if ($params['type_voucher'] == $voucher->type) {
                            if (!$filter_status) {
                                $usage[$voucher->code . '-' . $ppdb->id]['register_number'] = $ppdb->register_number;
                                $usage[$voucher->code . '-' . $ppdb->id]['unit'] = $ppdb->unit->name;
                                $usage[$voucher->code . '-' . $ppdb->id]['name'] = $ppdb->name;
                                $usage[$voucher->code . '-' . $ppdb->id]['code'] = $voucher->code;
                                $usage[$voucher->code . '-' . $ppdb->id]['type'] = $type;
                                $usage[$voucher->code . '-' . $ppdb->id]['free'] = $free;
                                $usage[$voucher->code . '-' . $ppdb->id]['limit'] = $voucher->usage_limit;
                                $usage[$voucher->code . '-' . $ppdb->id]['usage_remining'] = $usage_remaining;
                                $usage[$voucher->code . '-' . $ppdb->id]['total_usage'] = $total_used;
                                $usage[$voucher->code . '-' . $ppdb->id]['status'] = $total_used ? 'claimed' : 'available';
                                $usage[$voucher->code . '-' . $ppdb->id]['label_color'] = $total_used ? 'danger' : 'success';
                            } else {
                                $status_voucher = ($total_used) ? 'claimed' : 'available';
                                if ($params['status'] == $status_voucher) {
                                    $usage[$voucher->code . '-' . $ppdb->id]['register_number'] = $ppdb->register_number;
                                    $usage[$voucher->code . '-' . $ppdb->id]['unit'] = $ppdb->unit->name;
                                    $usage[$voucher->code . '-' . $ppdb->id]['name'] = $ppdb->name;
                                    $usage[$voucher->code . '-' . $ppdb->id]['code'] = $voucher->code;
                                    $usage[$voucher->code . '-' . $ppdb->id]['type'] = $type;
                                    $usage[$voucher->code . '-' . $ppdb->id]['free'] = $free;
                                    $usage[$voucher->code . '-' . $ppdb->id]['limit'] = $voucher->usage_limit;
                                    $usage[$voucher->code . '-' . $ppdb->id]['usage_remining'] = $usage_remaining;
                                    $usage[$voucher->code . '-' . $ppdb->id]['total_usage'] = $total_used;
                                    $usage[$voucher->code . '-' . $ppdb->id]['status'] = $total_used ? 'claimed' : 'available';
                                    $usage[$voucher->code . '-' . $ppdb->id]['label_color'] = $total_used ? 'danger' : 'success';
                                    $usage[$voucher->code . '-' . $ppdb->id]['claimed_date'] = $total_used ? 'danger' : 'success';
                                }

                            }
                        }


                    }
                }
            }


        }

//        $collect = collect();
//        $collect->push($usage);
//
//        $collection = collect($usage);

        $this->collections = collect($usage);

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

    public function map($usage): array
    {

        return [
            $usage['unit'],
            $usage['register_number'],
            $usage['name'],
            $usage['code'],
            $usage['type'],
            $usage['free'],
            $usage['limit'],
            $usage['status'],
        ];
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
            'REGISTER NUMBER',
            'NAMA SISWA',
            'CODE',
            'TYPE VOUCHER',
            'VOUCHER',
            'LIMIT',
            'STATUS',
        ];
    }
}
