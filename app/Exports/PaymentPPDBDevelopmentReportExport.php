<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class PaymentPPDBDevelopmentReportExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
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
                        $isFirstRow ? $user['is_dispensation'] : '',
                        $isFirstRow ? $user['total_final_fee'] : '',
                        $isFirstRow ? $user['remaining_balance'] : '',
                        $isFirstRow ? $user['created_at'] : '',

                        $detail['installment_number'] ?? '',
                        $detail['virtual_account'] ?? '',
                        $detail['date'] ?? '-',
                        $detail['nominal'] ?? '-',
                        $detail['amount_paid'] ?? '-',
                        $detail['status'] ?? '-',
                    ];

                    $isFirstRow = false;
                }
            } else {
                $rows[] = [
                    $isFirstRow ? $user['name'] : '',
                    $isFirstRow ? $user['register_number'] : '',
                    $isFirstRow ? $user['unit'] : '',
                    $isFirstRow ? $user['is_dispensation'] : '',
                    $isFirstRow ? $user['total_final_fee'] : '',
                    $isFirstRow ? $user['remaining_balance'] : '',
                    $isFirstRow ? $user['created_at'] : '',
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
            'Dispensasi',
            'Nominal Pembayaran',
            'Sisa Pembayaran',
            'Tgl Dibuat',
            'Keterangan',
            'Virtual Account',
            'Tgl Bayar',
            'Tagihan',
            'Tagihan Dibayar',
            'Status',
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
