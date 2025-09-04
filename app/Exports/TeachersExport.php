<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Teacher;

class TeachersExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Teacher::with('user')->get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($teacher): array
    {
        return [
            $teacher->nik,
            $teacher->name,
            $teacher->email,
            $teacher->mobile_phone,
            $teacher->address,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function headings(): array
    {
        return [
            'NIK',
            'NAME',
            'EMAIL',
            'MOBILE PHONE',
            'ADDRESS',
        ];
    }
}
