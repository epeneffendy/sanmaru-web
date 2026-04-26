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
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PPDBListStudentExport  implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{

    use Exportable;

    private $isTemplate = false;
    private $collections = null;
    private $period, $unit, $stage;

    public function __construct($params)
    {
        $this->period = $params['periode'];
        $this->unit = $params['unit'];


        $passedUserIds = $this->studentPassed($this->period);

        $ppdbUsers = PPDBUser::where('unit_id', $this->unit)
            ->where('periode', $this->period)
            ->whereIn('ppdb_users.id', $passedUserIds)
            ->select('ppdb_users.id', 'name', 'register_number', 'unit_id', 'periode')
            ->get();

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
            'KELAS',
            'NISN',
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

    public function title(): string
    {
        return 'Data Siswa';
    }

    public function studentPassed($period)
    {
        $ppdbUser = PPDBUser::where('periode', $period)->get();
        $arr = [];
        foreach ($ppdbUser as $user) {
            if (($user->status == PPDBUser::STATUS_ACCEPTED) && ($user->user->type == 'ppdb')) {
                $arr[] = $user->id;
            }
        }

        return $arr;
    }

}