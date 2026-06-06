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
            'periode' => $ppdbUser['periode'] ?? '-',
            'tgl_daftar' => $ppdbUser['created_at'] ?? '-',
            'registration' => ($ppdbUser['detail']['registration']) ? 'Lunas' : 'Belum Terbayarkan',
            'administrasi' => ($ppdbUser['detail']['administrasi']) ? 'Data Lengkap' : 'Data Belum Lengkap',
            'statement_letter_verif' => !empty($ppdbUser['detail']['statement_letter_verif']) ? 'Sudah Terverifikasi' : (!empty($ppdbUser['detail']['statement_letter']) ? 'Sudah Diunggah' : 'Belum Diunggah'),
            'order_uniform' => ($ppdbUser['detail']['order_uniform']) ? 'Sudah Melakukan Pembelian Seragam' : 'Belum Melaukan Pembelian Seragam',
            'final_accpetance' => ($ppdbUser['detail']['final_accpetance']) ? 'Telah Diterima Sebagai Siswa' : 'Belum Diterima Sebagai Siswa',
            'class_nisn' => ($ppdbUser['detail']['class_nisn']) ? 'Kelas & NISN Sudah Ditentukan' : 'Kelas & NISN Belum Ditentukan',

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Register Number',
            'Unit',
            'Periode',
            'Tgl Daftar',
            'Pembayaran Formulir',
            'Data Administrasi',
            'Surat Pernyataan',
            'Pembelian Seragam',
            'Penerimaan Akhir',
            'NISN & Kelas',
        ];
    }
}
