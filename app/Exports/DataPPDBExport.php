<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Helpers\PriceHelper;
use App\Models\PPDBUser;
use App\Models\Period;

class DataPPDBExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        return PPDBUser::byUserRole()->where('periode', $this->filter->id)->with('user', 'unit')->get();
    }

    public function setFilter($value)
    {
        $this->filter = Period::findOrFail($value);
    }

    public function map($PPDBUser): array
    {
        if ($PPDBUser->isEmailVerified) {
            $status_email = 'Terverifikasi';
        } else {
            $status_email = 'Belum Terverifikasi';
        }

        if ($PPDBUser->isPaymentStatusVerified) {
            $status_pembayaran = 'Terverifikasi';
        } else {
            $status_pembayaran = 'Belum Terverifikasi';
        }


        return [
            $PPDBUser->unit->name,
            PriceHelper::virtualAccountNumber($PPDBUser).' ',
            $PPDBUser->register_number,
            $PPDBUser->name,
            $PPDBUser->user->email,
            $status_email,
            $status_pembayaran,
            $PPDBUser->getPaymentFormImageUrl(),
            date_format($PPDBUser->created_at,"d-m-Y H:i"),
            $this->filter->name,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'Unit',
            'No VA',
            'No Registrasi',
            'Nama',
            'Email',
            'Status Email',
            'Status Pembayaran',
            'link bukti Pembayaran',
            'tanggal Pendaftaran',
            'Periode',
        ];
    }
}
