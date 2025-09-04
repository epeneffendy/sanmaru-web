<?php

namespace App\Exports;

use App\Models\Finance;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return Finance::get();
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($unit): array
    {
        return [
            $unit->code,
            $unit->name,
            $unit->nominal_default,
        ];
    }

    public function headings(): array
    {
        return [
            'CODE',
            'NAME',
            'NOMINAL DEFAULT'
        ];
    }
}
