<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Vendor;

class VendorsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Vendor::with('user')->get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($vendor): array
    {
        return [
            $vendor->name,
            $vendor->address,
            $vendor->user->email,
            $vendor->city,
            $vendor->pic,
            $vendor->user->mobile_phone,
            $vendor->nota_number,
            $vendor->nota_date,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'ADDRESS',
            'EMAIL',
            'CITY',
            'PIC',
            'MOBILE PHONE',
            'NOTA NUMBER',
            'NOTA DATE',
        ];
    }
}
