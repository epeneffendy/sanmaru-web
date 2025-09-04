<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Student;
use App\Models\CampusUnit;
use App\Models\Campus;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    use Exportable;

    private $isTemplate = false;

    public function __construct($params)
    {
        $students = Student::with('user', 'class', 'payment', 'additionalData');

        if (array_key_exists('search', $params) && array_key_exists('scope', $params) && $params['search']) {
            switch ($params['scope']) {
                case 'name':
                    $students->where('name', 'like', '%' . $params['search'] . '%');
                    break;
                case 'nis':
                    $students->where('nis', 'like', '%' . $params['search'] . '%');
                    break;
                default:
                    break;
            }
        }

        if (array_key_exists('unit', $params) && $params['unit']) {
            $students->whereHas('class', function($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $students->where('school_year', $params['year']);
        }

        $this->collections = $students->get();

    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect([
                (object) [
                    'nis' => '119xxx',
                    'name' => 'Dummy',
                    'user' => (object) [
                        'username' => 'dummy1'
                    ],
                    'email' => 'dummy@dummy.com',
                    'mobile_phone' => '085331124xxx',
                    'address' => 'Jl. Alamat',
                    'campus' => (object) [
                        'name' => 'Kampus Santa Maria Surabaya'
                    ],
                    'class' => (object) [
                        'name' => 'IX A',
                        'unit' => (object) [
                            'id' => '5',
                            'name' => 'SMA-SURABAYA'
                        ]
                    ],
                    'school_year' => date('Y'),
                    'register_number' => '2305xxx',
                    'additionalData' => (object) [
                        'gender' => 'female',
                        'place_of_birth' => 'Surabaya',
                        'date_of_birth' => date('d/m/Y'),
                        'city' => 'Surabaya',
                        'region' => 'Jawa Timur',
                        'country' => 'Indonesia',
                        'religion' => 'Katholik',
                    ],
                    'is_dummy' => true,
                ]
            ]);
        }

        return $this->collections;
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($student): array
    {
        $unitId = @$student->class->unit->id;
        $campusUnit = CampusUnit::where('unit_id', '=', $unitId)->first();

        return [
            $student->nis,
            $student->name,
            $student->user->username,
            $student->email,
            $student->mobile_phone,
            $student->address,
            @$campusUnit->campus->name,
            @$student->class->unit->name,
            @$student->class->name,
            $student->school_year,
            $student->register_number,
            @$student->additionalData->gender,
            @$student->additionalData->place_of_birth,
            @$student->additionalData->date_of_birth,
            @$student->additionalData->city,
            @$student->additionalData->region,
            @$student->additionalData->country,
            @$student->additionalData->religion,
            @$student->is_dummy ? '*hapus data dummy ini' : '',
            @$student->is_dummy ? '*nis dan register number wajib diisi salah satu' : ''
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER
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
            'KAMPUS',
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
            '',
            '',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:Q1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                if ($this->isTemplate) {
                    $event->sheet->getDelegate()->getStyle('A2:S2')->getFont()->getColor()->setARGB('DD4B39');
                }
            },
        ];
    }
}
