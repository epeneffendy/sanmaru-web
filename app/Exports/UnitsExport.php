<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Unit;

class UnitsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Unit::get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($unit): array
    {
        return [
            $unit->name,
            $unit->city,
            $unit->unit_code,
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'CITY',
            'UNIT CODE'
        ];
    }
}
