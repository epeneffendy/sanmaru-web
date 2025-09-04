<?php

namespace App\Exports;

use App\Enums\ProductOrderPaymentTypeEnum;
use Log;
use Exception;
use Carbon\Carbon;
use App\Models\ProductOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class KantinProductOrdersExport implements WithColumnFormatting, FromView, ShouldAutoSize
{
    use Exportable;

    private $collections;
    private $params;
    private $merged;
    private $user;

    public function failed(Exception $e)
    {
        Log::error($e->getMessage());
    }

    public function __construct($params = [], $user)
    {
        $this->params = $params;
        $this->user = $user;
    }

    public function view(): View
    {
        return view('exports.product_order_kantin', [
            'productOrders' => $this->collection(),
            'headings' => $this->headings(),
            'merged' => $this->merged
        ]);
    }

     public function columnFormats(): array
    {
        return [
            'B' => '#0',
            'C' => '#0'
        ];
    }

    public function collection()
    {
        $collect = collect();
        $params = $this->params;

        $productOrder = ProductOrder::query()
        ->with([
            'productOrderDetails',
            'productOrderDetails.product',
            'productOrderDetails.product.vendor',
            'productOrderDetails.productDetail',
            'user',
            'user.ppdb',
            'user.ppdb.unit',
            'user.student',
            'user.student.class',
            'user.student.class.unit',
        ])->where('payment_type', ProductOrderPaymentTypeEnum::KANTIN);

        $productOrder = $productOrder->whereHas('user', function ($query) use ($params) {
            return $query->whereHas('ppdb', function ($query) use ($params) {
                $query = $query->whereHas('unit', function ($query) use ($params) {
                    $query = $query->byUserRole($this->user);
                    if (isset($params['unit']) && $params['unit']) {
                        $query = $query->where('id', $params['unit']);
                    }
                });
                if (isset($params['search']) && $params['search'] && isset($params['scope']) && $params['scope']) {
                    $query = $query->where($params['scope'], 'like', '%'.$params['search'].'%');
                }
                if (isset($params['period']) && $params['period']) {
                    $query = $query->whereRaw("LEFT(`ppdb_users`.`register_number`, 2) = '".substr($params['period'], -2). "'");
                }
                return $query;
            })->orWhereHas('student', function ($query) use ($params) {
                $query = $query->whereHas('class.unit', function ($query) use ($params) {
                    $query = $query->byUserRole($this->user);
                    if (isset($params['unit']) && $params['unit']) {
                        $query = $query->where('id', $params['unit']);
                    }
                    return $query;
                });
                if (isset($params['search']) && $params['search'] && isset($params['scope']) && $params['scope']) {
                    if ($params['scope'] == 'register_number') {
                        //disable
                        $query = $query->whereRaw("0 = 1");
                    } else {
                        $query = $query->where($params['scope'], 'like', '%'.$params['search'].'%');
                    }
                }
                if (isset($params['period']) && $params['period']) {
                    $query = $query->where('school_year', $params['period']);
                }
                return $query;
            });
        });

        if (isset($params['status']) && $params['status']) {
            if ($params['status'] == 'payment_not_confirmed') {
                $productOrder = $productOrder->paymentNotConfirmed();
            }
            if ($params['status'] == 'payment_uploaded') {
                $productOrder = $productOrder->paymentUploaded();
            }
            if ($params['status'] == 'payment_confirmed') {
                $productOrder = $productOrder->paymentConfirmed();
            }
            if ($params['status'] == 'cancel') {
                $productOrder = $productOrder->canceled();
            }
        }

        if (isset($params['date_range']) && $params['date_range']) {
            $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
            $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
            $productOrder = $productOrder->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd);
        }

        $this->merged = [];
        $rowNo = 0;

        $productOrders = $productOrder->orderBy('created_at', 'desc')->get();
        if (!$productOrders->isEmpty()) {
            foreach ($productOrders as $productOrder) {
                $this->merged[$rowNo] = ($rowNo+$productOrder->productOrderDetails->count())-1;
                foreach ($productOrder->productOrderDetails as $coll) {
                    $collect->push([
                        'invoice_no' => $productOrder->invoice_no,
                        'nama_anak' => $productOrder->user->name,
                        'unit' => @$productOrder->user->unit_name,
                        'nama_product' => $coll->product->name,
                        'ukuran' => $coll->productDetail->size,
                        'nama_vendor' => @$coll->product->stand->name, //nama_vendor
                        'jumlah' => $coll->quantity, //jumlah_pesanan
                        'harga' => $coll->productDetail->price, //harga
                        'jumlah_pesanan' => $productOrder->productOrderDetails->count(),
                        'total_harga_pesanan' => $productOrder->productOrderDetails->sum('total_price'),
                        // 'grand_total' => $productOrder->grand_total,
                        'status' => $productOrder->status,
                        'status_pembayaran' => $productOrder->label_konfirmasi_pembayaran,
                        'tanggal' => date('d-M-Y', strtotime($productOrder->created_at))
                    ]);
                    $rowNo++;
                }
            }
        }

        return $collect;
    }

    public function headings(): array
    {
        return [
            'NO TAGIHAN',
            'NAMA ANAK',
            'UNIT',
            'NAMA ANAK (non-merge)',
            'UNIT (non-merge)',
            'NAMA PRODUCT',
            'UKURAN / VARIAN',
            'NAMA STAND',
            'JUMLAH PESANAN',
            'HARGA',
            'TOTAL JUMLAH PESANAN',
            'TOTAL HARGA PESANAN',
            'STATUS PESANAN',
            'STATUS PEMBAYARAN',
            'TANGGAL PESANAN'
        ];
    }

    private function mappingVoucherPesanan(ProductOrder $productOrder)
    {
        $string = '';
        if ($productOrder->voucher) {
            $string = json_decode($productOrder->voucher, TRUE)['code'];
        }

        return $string;
    }
}
