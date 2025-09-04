<?php

namespace App\Exports;

use App\Models\CustomForm;
use App\Models\CustomFormColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Helpers\PriceHelper;
use App\Models\Period;
use App\Models\PPDBUser;
use PHPUnit\Exception;

class CustomFormExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $custom, $params;

    public function __construct($id, $params = null)
    {
        $customForm = CustomForm::where('id', $id)->first();
        $this->custom = $customForm;
        $this->params = $params;
    }

    public function failed(Exception $e)
    {
        Log::error($e->getMessage());
    }

    public function view(): View
    {
        return view('exports.custom_form', [
            'headings' => $this->headings(),
            'period' => $this->custom,
            'forms' => $this->collection(),
            'params' => $this->params,
        ]);

    }

    public function collection()
    {
        $customForm = CustomForm::with('columnInputs', 'columnInputs.ppdb_user', 'periods', 'unit')->find($this->custom->id);

        return $customForm;
    }

    public function headings(): array
    {
        return [
            'No',
            'Register Number',
            'Student',
        ];
    }
}
