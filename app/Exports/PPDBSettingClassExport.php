<?php

namespace App\Exports;

// Tambahkan import ini di bagian atas
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PPDBSettingClassExport implements WithMultipleSheets
{
    use Exportable;

    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new PPDBListStudentExport($this->params);

        $sheets[] = new PPDBListClassExport($this->params);

        return $sheets;
    }
}