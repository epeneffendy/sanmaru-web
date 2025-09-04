<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Classes;

class ClassesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Classes::with('unit')->get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($class): array
    {
        return [
            $class->unit->name,
            $class->name,
            $class->unit_class
        ];
    }

    public function headings(): array
    {
        return [
            'UNIT NAME',
            'CLASS NAME',
            'UNIT CLASS'
        ];
    }
}
