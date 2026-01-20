<?php

namespace App\Exports;

use App\Models\PPDBUser;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\User;
use App\Models\Voucher;
use App\Services\VoucherService;
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

class VoucherUsageMissExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $collections = null;

    public function __construct($params)
    {

        $related = [
            'user',
            'user.student',
            'user.student.class',
            'user.student.class.unit',
            'user.ppdb',
            'user.ppdb.unit',
            'productOrderDetails',
            'productOrderDetails.productDetail',
        ];
        $searchScopes = [
            'student_name' => 'Nama Siswa',
            'register_number' => 'Nomor Registrasi Siswa'
        ];

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
            $productOrders->whereHas('user.ppdb', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            })->orWhereHas('user.student.class', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if ($related) {
            $productOrders->with($related);
        }

        $productOrders = $productOrders->where('voucher', 'like', '%"type":"free_product"%')
            ->with('productOrderDetails', 'user', 'user.ppdb', 'user.student')
            ->orderBy('invoice_no', 'ASC')->get();


        $allMissProductIds = [];
        $productOrders = $productOrders->filter(function ($item) use (&$allMissProductIds) {
            $missProductIds = [];
            $voucher = json_decode($item->voucher, TRUE);

            if ($voucher != null) {
                $productIds = $item->productOrderDetails->keyBy('product_id')->all();
                $rules = json_decode($voucher['rule'], TRUE);

                if (is_array($rules) || is_object($rules)) {
                    foreach ($rules as $rule) {
                        if (!array_key_exists($rule, $productIds)) {
                            $missProductIds[$rule] = $rule;
                        }
                    }

                    $allMissProductIds = array_merge($allMissProductIds, $missProductIds);
                    $item->missProductIds = $missProductIds;

                    return count($missProductIds);
                }
            }
        });

        $products = Product::whereIn('id', $allMissProductIds)->get()->keyBy('id')->all();

        $usage = [];
        foreach ($productOrders as $order) {

            $name = '';
            if ($order->user->student) {
                $name = $order->user->student->name;
            } elseif ($order->user->ppdb) {
                $name = $order->user->ppdb->name;
            }

            $product = '';
            foreach ($order->missProductIds as $missProductId) {
                $product .= $products[$missProductId]->name . ', ';
            }

            $product = substr($product, 0, -2);

            $status = '';
            if ($order->status == 'new_order') {
                $status = 'Order Baru';
            }

            if ($order->status == 'confirmed') {
                $status = 'Terkonfirmasi';
            }

            if ($order->status == 'done') {
                $status = 'Pengambilan';
            }

            if ($order->status == 'pickup') {
                $status = 'Selesai';
            }

            if ($order->status == 'cancel') {
                $status = 'Batal';
            }

            $usage[$order->invoice_no]['no_invoice'] = $order->invoice_no;
            $usage[$order->invoice_no]['name'] = $name;
            $usage[$order->invoice_no]['product'] = $product;
            $usage[$order->invoice_no]['voucher'] = json_decode($order->voucher, TRUE)['code'];
            $usage[$order->invoice_no]['status'] = $status;
        }

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
            $usage['no_invoice'],
            $usage['name'],
            $usage['product'],
            $usage['voucher'],
            $usage['status'],
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'NO TAGIHAN',
            'NAMA SISWA',
            'VOUCHER',
            'PRODUCT',
            'STATUS',
        ];
    }
}
