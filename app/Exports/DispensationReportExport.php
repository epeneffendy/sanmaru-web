<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class DispensationReportExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $ppdbUser;

    public function __construct(array $ppdbUser)
    {
        $this->ppdbUser = $ppdbUser;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->ppdbUser as $user) {
            $isFirstRow = true;

            if (isset($user['detail']) && is_array($user['detail']) && count($user['detail']) > 0) {
                foreach ($user['detail'] as $detail) {
                    $rows[] = [
                        $isFirstRow ? $user['name'] : '',
                        $isFirstRow ? $user['register_number'] : '',
                        $isFirstRow ? $user['unit'] : '',
                        $isFirstRow ? $user['dispensation_type'] : '',
                        $isFirstRow ? $user['dispensation_mode'] : '',
                        $isFirstRow ? $this->formatRupiah($user['actual_cost']) : '',
                        $isFirstRow ? $this->formatRupiah($user['total_final_fee']) : '',
                        $isFirstRow ? $this->formatRupiah($user['remaining_balance']) : '',
                        $isFirstRow ? $user['created_at'] : '',

                        $detail['installment_number'] ?? '',
                        $detail['virtual_account'] ?? '',
                        $detail['date'] ?? '-',
                        $this->formatRupiah($detail['nominal'] ?? 0),
                        $this->formatRupiah($detail['amount_paid'] ?? 0),
                        $detail['status'] ?? '',
                    ];

                    $isFirstRow = false;
                }
            } else {
                $rows[] = [
                    $user['name'] ?? '',
                    $user['register_number'] ?? '',
                    $user['unit'] ?? '',
                    $user['dispensation_type'] ?? '',
                    $user['dispensation_mode'] ?? '',
                    $this->formatRupiah($user['actual_cost'] ?? 0),
                    $this->formatRupiah($user['total_final_fee'] ?? 0),
                    $this->formatRupiah($user['remaining_balance'] ?? 0),
                    $user['created_at'] ?? '',
                    '-', '-', '-', '-', '-', '-'
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'No. Registrasi',
            'Unit',
            'Jenis Dispensasi',
            'Mode Dispensasi',
            'Biaya Asli (Rp)',
            'Total Biaya Akhir (Rp)',
            'Sisa Saldo (Rp)',
            'Tanggal Dibuat',
            'Nama Tagihan / Cicilan',
            'Virtual Account',
            'Tanggal Jatuh Tempo',
            'Nominal Tagihan (Rp)',
            'Jumlah Dibayar (Rp)',
            'Status Pembayaran',
        ];
    }

    /**
     * Menggunakan registerEvents sebagai pengganti WithStyles
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Rentang kolom A sampai O pada baris 1 (Header)
                $cellRange = 'A1:O1';

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0D6EFD'] // Biru
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    private function formatRupiah($angka)
    {
        if (!is_numeric($angka)) return $angka;
        return number_format((float)$angka, 0, ',', '.');
    }
}
