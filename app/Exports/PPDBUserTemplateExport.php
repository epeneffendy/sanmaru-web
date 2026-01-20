<?php

namespace App\Exports;

use App\Models\CampusUnit;
use App\Models\PPDBUser;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PPDBUserTemplateExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $collections = null;

    public function __construct($params)
    {

        $ppdb = PPDBUser::where([
            'unit_id' => $params['unit'],
            'school_year' => $params['school_year'],
        ])->get();

        $datas = [];
        foreach ($ppdb as $ind => $item) {
            if($item->isStatementLetterUploaded && $item->isSubmitted){
                $campus = CampusUnit::where('unit_id',$item->unit_id)->first();

                $datas[$ind]['nis'] = '';
                $datas[$ind]['name'] = $item->name;
                $datas[$ind]['username'] = $item->user->username;
                $datas[$ind]['email'] = $item->user->email;
                $datas[$ind]['mobile_phone'] = $item->user->mobile_phone;
                $datas[$ind]['address'] = $item->address;
                $datas[$ind]['campus'] = $campus->campus->name;
                $datas[$ind]['unit'] = $item->unit->name;
                $datas[$ind]['class_room'] = '';
                $datas[$ind]['school_year'] = $item->school_year;
                $datas[$ind]['register_number'] = $item->register_number;
                $datas[$ind]['gender'] = $item->gender;
                $datas[$ind]['place_of_birth'] = $item->place_of_birth;
                $datas[$ind]['date_of_birth'] = $item->date_of_birth;
                $datas[$ind]['city'] = $item->city;
                $datas[$ind]['region'] = $item->region;
                $datas[$ind]['country'] = $item->country;
                $datas[$ind]['religion'] = $item->religion;
            }
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
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NAME',
            'USERNAME',
            'EMAIL',
            'MOBILE PHONE',
            'ADDRESS',
            'CAMPUS',
            'UNIT',
            'CLASS NAME',
            'SCHOOL YEAR',
            'REGISTER NUMBER',
            'GENDER',
            'PLACE OF BIRTH',
            'DATE OF BIRTH',
            'CITY',
            'REGION',
            'COUNTRY',
            'RELIGION',
        ];
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($row): array
    {

        return [
            $row['nis'],
            $row['name'],
            $row['username'],
            $row['email'],
            $row['mobile_phone'],
            $row['address'],
            $row['campus'],
            $row['unit'],
            $row['class_room'],
            $row['school_year'],
            $row['register_number'],
            $row['gender'],
            $row['place_of_birth'],
            $row['date_of_birth'],
            $row['city'],
            $row['region'],
            $row['country'],
            $row['religion'],
        ];
    }
}
