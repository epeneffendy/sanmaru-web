<?php

namespace App\Exports;

use App\Models\PPDBUser;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Services\VoucherService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Vendor;

class VoucherUsageExportMiss implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $collections = null;

    public function __construct($request)
    {
dd($request);

        $usage = '';

        $this->collections = collect($usage);

        return $this->collections;

    }

    public function collection()
    {
        return $this->collections;
    }

    public function setTemplate(bool $value)
    {

        $this->isTemplate = $value;
    }

    public function map($usage): array
    {

        return [
            'xxxxx',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
//            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'UNIT',
            'NAMA SISWA',
            'CODE',
            'TYPE VOUCHER',
            'VOUCHER',
            'LIMIT',
            'STATUS',
        ];
    }
}
