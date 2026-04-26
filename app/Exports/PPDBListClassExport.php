<?php

namespace App\Exports;

use App\Models\Classes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PPDBListClassExport implements FromCollection, WithTitle, WithHeadings
{
    private $collections = null;
    private $unit;

    public function __construct($params)
    {
        $this->unit = $params['unit'];

        $classes = Classes::select(['name'])->where('unit_id', $this->unit)->get();

        $this->collections = collect($classes);

        return $this->collections;
    }


    public function collection()
    {
        return $this->collections;
    }

    public function title(): string
    {
        return 'Data Kelas';
    }

    public function headings(): array
    {
        return [
            'KELAS',
            'UNIT KELAS'
        ];
    }


}