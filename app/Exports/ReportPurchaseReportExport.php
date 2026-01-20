<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportPurchaseReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $productOrders = [];
    private $no = 1;

    public function __construct(Collection $productOrders)
    {
        $this->productOrders  = $productOrders;
    }

    public function collection()
    {
        return $this->productOrders;
    }

    public function map($productOrder): array
    {

        if ($productOrder->payment_status == 'confirmed'){
            $status_payment = 'Sudah Terbayarkan';
        }elseif ($productOrder->payment_status == 'new_order'){
            $status_payment = 'Belum Terbayarkan';
        }else{
            $status_payment = 'Batal Order';
        }
        return [
            'no' => $this->no++,
            "unit_name" => $productOrder->unit_name,
            "product_name" => $productOrder->product_name,
            "size" => $productOrder->size,
            "qty" => $productOrder->qty,
            "payment_status" => $status_payment,
            "pickup_status" => ($productOrder->pickup_status == 'pickup') ? 'Sudah Diambil' : 'Belum Diambil',

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Unit Sekolah',
            'Nama Seragam',
            'Ukuran Seragam',
            'Jumlah',
            'Status Pembayaran',
            'Status Pengembilan',
        ];
    }
}
