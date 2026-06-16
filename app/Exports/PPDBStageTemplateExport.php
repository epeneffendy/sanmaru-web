<?php

namespace App\Exports;

use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use App\Models\Stage;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PPDBStageTemplateExport  implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithEvents
{

    use Exportable;

    private $isTemplate = false;
    private $collections = null;
    private $period, $unit, $stage;

    public function __construct($params)
    {
        $this->period = $params['period'];
        $this->unit = $params['unit'];
        $this->stage = $params['stage'];


        $passedUserIds = $this->studentPassed($this->period);

        $stage = Stage::byUserRole()->where('id', $this->stage)->firstOrFail();

        $ppdbUsers = PPDBUser::where('unit_id', $this->unit)
            ->where('periode', $this->period)
            ->where('period_verified', '<>', 'waiting')
            ->whereIn('ppdb_users.id', $passedUserIds)
            ->select('ppdb_users.id', 'name', 'register_number', 'unit_id', 'periode', 'ppdb_user_stages.passed', 'ppdb_user_stages.note')
            ->leftJoin('ppdb_user_stages', function ($join) use ($stage) {
                return $join->on('ppdb_users.id', '=', 'ppdb_user_stages.ppdb_user_id')->where('stage_id', $stage->id);
            })
            ->get();

        if ($stage->is_opening_shop_feature) {
            $accepted = [];
            $development = Stage::where('unit_id', $this->unit)->where('periode', $this->period)->where('active', 1)->where('is_opening_development_feature', 1)->first();
            if ($development) {
                $accepted = PPDBUserStage::where('stage_id', $development->id)
                    ->where('passed', 1)->pluck('ppdb_user_id')->all();
            }

            $ppdbUsers = $ppdbUsers->filter(function ($ppdbUser) use ($accepted) {
                return in_array($ppdbUser->id, $accepted);
            })->values();

        }

        $datas = [];
        foreach ($ppdbUsers as $ind => $user) {
            $unit = Unit::where('id',$user->unit_id)->first();

            $datas[$ind]['register_number'] = $user->register_number;
            $datas[$ind]['name'] = $user->name;
            $datas[$ind]['unit']= $unit->name;
        }

        $this->collections = collect($datas);

        return $this->collections;

    }

    public function collection()
    {
        return $this->collections;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'REGISTER NUMBER',
            'NAME',
            'UNIT',
            'STATUS',
            'KETERANGAN 1',
            'KETERANGAN 2',
            'KETERANGAN 3',
        ];
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($row): array
    {

        return [
            $row['register_number'],
            $row['name'],
            $row['unit']
        ];
    }

    public function studentPassed($period)
    {
        $ppdbUser = PPDBUser::where('periode', $period)->get();
        $arr = [];
        foreach ($ppdbUser as $user) {
            if ($user->isDataCompleteWhitoutBca) {
                $arr[] = $user->id;
            }
        }

        return $arr;
    }


    public function registerEvents(): array
    {
        return [
//            AfterSheet::class => function(AfterSheet $event) {
//                // Menulis Note di baris 1 kolom H (Baris 1, Kolom 8)
//                $event->sheet->getDelegate()->setCellValue('I1', 'Note : Silahkan isi status dengan keterangan seperti berikut');
//
//                // Menulis pilihan status di baris bawahnya
//                $event->sheet->getDelegate()->setCellValue('I2', 'lolos');
//                $event->sheet->getDelegate()->setCellValue('I3', 'pending');
//                $event->sheet->getDelegate()->setCellValue('I4', 'tidak lolos');
//
//                // Opsional: Menebalkan tulisan "Note" agar lebih jelas
//                $event->sheet->getDelegate()->getStyle('I1')->getFont()->setBold(true);
//            },
        ];
    }
}
