<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\User;
use Illuminate\Support\Collection;

class DistributionOrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            "name" => $productOrder->name,
            "product_name" => $productOrder->product_name,
            "size" => $productOrder->size,
            "qty" => $productOrder->qty,

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Nama Seragam',
            'Ukuran Seragam',
            'Jumlah',
        ];
    }
}
