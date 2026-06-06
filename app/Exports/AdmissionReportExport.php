<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class AdmissionReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $ppdbUsers = [];
    private $no = 1;

    public function __construct(Collection $ppdbUsers)
    {
        $this->ppdbUsers = $ppdbUsers;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->ppdbUsers;
    }

    public function map($ppdbUser): array
    {
        // Sesuaikan key array/object di bawah ini dengan isi data dari getAdmissionReport()
        return [
            'no' => $this->no++,
            'register_number' => $ppdbUser['register_number'] ?? '-',
            'name' => $ppdbUser['name'] ?? '-',
            'unit' => $ppdbUser['unit'] ?? '-',
            'status' => $ppdbUser['label_status'] ?? $ppdbUser['status'] ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'No Pendaftaran',
            'Nama Siswa',
            'Unit',
            'Status',
        ];
    }
}
