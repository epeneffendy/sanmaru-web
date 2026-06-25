<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;

class RecapitulationAdmissionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        return [
            'no' => $this->no++,
            'unit_name' => $ppdbUser['unit_name'] ?? 0,
            'total_student' => $ppdbUser['total_student'] .' Siswa' ?? 0,
            'payment_registration' => $ppdbUser['payment_registration'] .' Siswa' ?? 0,
            'administration' => $ppdbUser['administration'] .' Siswa' ?? 0,
            'upload_statement_letter' => $ppdbUser['upload_statement_letter'] .' Siswa' ?? 0,
            'verif_statement_letter' =>$ppdbUser['verif_statement_letter'] .' Siswa' ?? 0,
            'order_uniform' => $ppdbUser['order_uniform'] .' Siswa' ?? 0,
            'final_stage' => $ppdbUser['final_stage'] .' Siswa' ?? 0,

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Unit',
            'Jumlah Pendaftar',
            'Pembayaran Formulir',
            'Administrasi',
            'Upload Surat Pernyataan',
            'Verifikasi Surat Pernyataan',
            'Pembelian Seragam',
            'Penerimaan Akhir',
        ];
    }
}
