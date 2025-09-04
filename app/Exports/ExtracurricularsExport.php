<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Extracurricular;

class ExtracurricularsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Extracurricular::with('class')->get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($extracurricular): array
    {
        return [
            $extracurricular->name,
            $extracurricular->code,
            $extracurricular->class->unit_class
        ];
    }

    public function headings(): array
    {
        return [
            'NAME',
            'CODE',
            'UNIT CLASS'
        ];
    }
}
