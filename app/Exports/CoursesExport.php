<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Course;

class CoursesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Course::with('unit')->get();
    }

    public function map($course): array
    {
        return [
            $course->unit ? $course->unit->name : "Undefined",
            $course->name,
            $course->code,
        ];
    }

    public function headings(): array
    {
        return [
            'UNIT NAME',
            'NAME',
            'CODE',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
