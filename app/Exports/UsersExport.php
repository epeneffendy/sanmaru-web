<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\User;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $isStudent = false;

    public function __construct($params)
    {
        $user = new User();
        if (!empty($params['user'])) {
            $name = $params['user'];
            $user = $user->whereRaw("LOWER(username) like '%". strtolower($name) ."%'")->orWhereRaw("LOWER(email) like '%". strtolower($name) ."%'");
        }
        if (!empty($params['type'])) {
            $user = $user->where('type', $params['type']);
            // https://aimsis.atlassian.net/browse/AIMSIS-10513
            if ($params['type'] == 'siswa'){
                $user->with('student');
                $this->isStudent = true;
            }
        }

        $this->collections = $user->get();
    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        return $this->collections;
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($user): array
    {
        return [
            $user->username,
            $user->mobile_phone,
            $user->email,
            $user->status,
            $user->type,
            ($this->isStudent) ? @$user->student->class->unit->name : NULL,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function headings(): array
    {
        $header = [
            'USERNAME',
            'MOBILE PHONE',
            'EMAIL',
            'STATUS',
            'PERAN',
            ($this->isStudent) ? 'UNIT SEKOLAH' : NULL,
        ];
        return (!$this->isTemplate) ? $header + ['TYPE'] : $header;
    }
}
