<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\User;
use Illuminate\Support\Collection;

class DevelopmentReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $ppdbUser = [];
    private $no = 1;

    public function __construct(Collection $ppdbUser)
    {
        $this->ppdbUser  = $ppdbUser;
    }

    public function collection()
    {
        return $this->ppdbUser;
    }

    public function map($ppdbUser): array
    {
        return [
            'no' => $this->no++,
            "name"=> $ppdbUser['name'],
            "register_number" => $ppdbUser['register_number'],
            "unit" => $ppdbUser['unit'],
            "payment" => $ppdbUser['label_payment'],
            "status" => $ppdbUser['label_status'],
            "voucher" => $ppdbUser['voucher']

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Register Number',
            'Unit',
            'Pembayaran',
            'Status',
            'Voucher',
        ];
    }

}
