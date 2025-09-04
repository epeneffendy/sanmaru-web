<?php

namespace App\Exports;

use Log;
use Exception;
use App\Helpers\Helper;
use App\Helpers\PriceHelper;
use App\Models\ProductOrder;
use App\Services\ProductOrderPickupService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ProductOrderPickupExport implements WithColumnFormatting, FromView, ShouldAutoSize
{
    use Exportable;

    private $collections;
    private $params;
    private $merged;

    public function failed(Exception $e)
    {
        Log::error($e->getMessage());
    }

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function view(): View
    {
        return view('exports.product_order_pickup', [
            'productOrders' => $this->collection(),
            'headings' => $this->headings(),
            'merged' => $this->merged
        ]);
    }

     public function columnFormats(): array
    {
        return [
            'B' => '#0',
        ];
    }

    public function collection()
    {
        $collect = collect();
        $productOrderPickupService = new ProductOrderPickupService();
        $related = [
            'user.student',
            'user.student.class',
            'user.student.class.unit',
            'productOrderDetails',
            'productOrderDetails.product',
            'productOrderDetails.product.vendor',
            'productOrderDetails.productDetail',
            'user',
            'user.ppdb',
            'user.ppdb.unit'
        ];
        $productOrders = $productOrderPickupService->filter($this->params, null, $related);

        $this->merged = [];
        $rowNo = 0;

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
                        'nama_vendor' => @$coll->product->vendor->name, //nama_vendor
                        'jumlah' => $coll->quantity, //jumlah_pesanan
                        'harga' => $coll->price, //harga
                        'jumlah_pesanan_harga' => ($coll->quantity * $coll->price),
                        'jumlah_pesanan' => $productOrder->productOrderDetails->count(),
                        'total_harga_pesanan' => $productOrder->grand_total_gross,
                        'voucher_code' => $this->mappingVoucherPesanan($productOrder), // voucher code
                        'total_voucher' => $productOrder->discount_total, //total_voucher
                        'grand_total' => $productOrder->grand_total,
                        'status_pengambilan' => $productOrder->pickup_and_schedule_status,
                        'jadwal_pengambilan' => $productOrder->pickup_date_schedule ? (\App\Helpers\Helper::tanggal($productOrder->pickup_date_schedule) . ($productOrder->alt_pickup_date_schedule ? " atau " . \App\Helpers\Helper::tanggal($productOrder->alt_pickup_date_schedule) : null) . " / " . \Carbon\Carbon::parse($productOrder->pickup_start_time)->format('H:i') . " - " . \Carbon\Carbon::parse($productOrder->pickup_end_time)->format('H:i')) : null,
                        'tanggal_pengambilan' => $productOrder->pickup_date ? date('d-M-Y H:i', strtotime($productOrder->pickup_date)) : null,
                        'updated_at' => $productOrder->updated_at
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
            'NAMA SISWA',
            'UNIT',
            // 'NAMA ANAK (non-merge)',
            // 'UNIT (non-merge)',
            'NAMA PRODUCT',
            'UKURAN',
            'NAMA VENDOR',
            'JUMLAH PESANAN',
            'HARGA',
            'JUMLAH PESANAN X HARGA',
            'TOTAL JUMLAH PESANAN',
            'TOTAL HARGA PESANAN',
            'VOUCHER CODE',
            'TOTAL VOUCHER',
            'TOTAL HARGA PESANAN - TOTAL VOUCHER',
            'STATUS PENGAMBILAN',
            'JADWAL PENGAMBILAN',
            'WAKTU PENGAMBILAN',
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
