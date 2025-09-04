<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class UserStagesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function collection()
    {
        return collect([
            [
                '2122001',
                'lolos',
                'Lolos peminatan IPA',
                'Dapat potongan harga 20%'
            ], [
                '2122002',
                'tidak lolos',
                '',
                ''
            ], [
                '2122003',
                'pending',
                '',
                ''
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'REGISTER NUMBER',
            'STATUS',
            'KETERANGAN 1',
            'KETERANGAN 2',
            'KETERANGAN 3'
        ];
    }
}
