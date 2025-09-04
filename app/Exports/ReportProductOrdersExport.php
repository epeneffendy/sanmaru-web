<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportProductOrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        return [
            'no' => $this->no++,
            "unit_name" => $productOrder['unit_name'],
            "product_name" => $productOrder['product_name'],
            "size" => $productOrder['size'],
            'student_type' => ($productOrder['student_type'] === User::PPDB) ? 'PPDB' : 'Regular',
            "price_vendor" => $productOrder['price_vendor'],
            "sell_price" => $productOrder['sell_price'],
            "count_product_sell" => $productOrder['count_product_sell'],
            "total_sell" => $productOrder['total_sell'],
            "profit" => $productOrder['profit'],
            "initial_stock" => $productOrder['initial_stock'],
            "available_stock" => $productOrder['available_stock'],
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Unit Sekolah',
            'Nama Seragam',
            'Ukuran Seragam',
            'Status Siswa',
            'Harga Vendor',
            'Harga Jual',
            'Jumlah Seragam Terjual',
            'Total',
            'Provit',
            'Stock Awal',
            'Sisa Stock',
        ];
    }
}
