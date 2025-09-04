<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Helpers\InputCollectionHelper;
use App\Traits\ImageHandler;
use App\Models\PPDBUser;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class PPDBUsersExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithCustomValueBinder
{
    use Exportable, ImageHandler;
    private $collections = null;
    private $units = [];

    private $additionalFields;
    private $uploadFields;

    public function __construct($params)
    {
        $this->additionalFields = $this->uploadFields = collect();

        $ppdbUsers = PPDBUser::notAccepted()->with(['parents', 'user', 'unit', 'order'])->orderBy('register_number', 'desc');

        if (isset($params['search']) && $params['search']) {
            if ($params['scope'] == 'register_number') {
                $ppdbUsers = $ppdbUsers->where('register_number', 'like', '%'.$params['search'].'%');
            } else {
                $ppdbUsers = $ppdbUsers->where('name', 'like', '%' . $params['search'] . '%');
            }
        }

        if (isset($params['unit']) && $params['unit']) {
            $ppdbUsers = $ppdbUsers->where('unit_id', $params['unit']);
        }
        if (isset($params['school_year']) && $params['school_year']) {
            // $ppdbUsers = $ppdbUsers->whereRaw("LEFT(`ppdb_users`.`register_number`, 2) = '". substr( $params['school_year'], -2)."'");
            $ppdbUsers = $ppdbUsers->where('school_year', $params['school_year']);
        }

        if (isset($params['period']) && $params['period'] == 'ongoing') {
            $ppdbUsers = $ppdbUsers->ongoingPeriod();
        }

        // $collection = [];

        // switch (isset($params['status']) && $params['status']) {
        //     case 'email_verified':
        //         $verifiedEmail = [];
        //         $unverifiedEmail = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->isEmailVerified) {
        //                 array_push($verifiedEmail, $value);
        //             } else {
        //                 array_push($unverifiedEmail, $value);
        //             }
        //         }

        //         $collection = array_merge($verifiedEmail, $unverifiedEmail); // Make verified data first before unverified data

        //         break;

        //     case 'payment_status':
        //         $confirmed = [];
        //         $uploaded = [];
        //         $null_data = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->isPaymentStatusComplete) {
        //                 array_push($confirmed, $value);
        //             } elseif ($value->isPaymentStatusVerified) {
        //                 array_push($uploaded, $value);
        //             } else {
        //                 array_push($null_data, $value);
        //             }
        //         }

        //         $collection = array_merge($confirmed, $uploaded, $null_data);

        //         break;

        //     case 'student_data':
        //         $CompleteData = [];
        //         $UnCompleteData = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->isDataComplete) {
        //                 array_push($CompleteData, $value);
        //             } else {
        //                 array_push($UnCompleteData, $value);
        //             }
        //         }

        //         $collection = array_merge($CompleteData, $UnCompleteData);

        //         break;

        //     case 'parent_data':
        //         $CompleteData = [];
        //         $UnCompleteData = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->isParentsComplete) {
        //                 array_push($CompleteData, $value);
        //             } else {
        //                 array_push($UnCompleteData, $value);
        //             }
        //         }

        //         $collection = array_merge($CompleteData, $UnCompleteData);

        //         break;

        //     case 'statement_letter':
        //         $confirmed = [];
        //         $uploaded = [];
        //         $null_data = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->IsStatementLetterConfirmed) {
        //                 array_push($confirmed, $value);
        //             } elseif ($value->isStatementLetterUploaded) {
        //                 array_push($uploaded, $value);
        //             } else {
        //                 array_push($null_data, $value);
        //             }
        //         }

        //         $collection = array_merge($confirmed, $uploaded, $null_data);

        //         break;

        //     case 'accepted':
        //         $accepted = [];
        //         $rejected = [];

        //         foreach ($ppdbUsers as $key => $value) {
        //             if ($value->isSubmitted && $value->isOrderConfirmed) {
        //                 array_push($accepted, $value);
        //             } else {
        //                 array_push($rejected, $value);
        //             }
        //         }

        //         $collection = array_merge($accepted, $rejected);

        //         break;

        //     default:
        //         # code...
        //         break;
        // }

        // $ppdbUsers = $collection;

        $this->collections = $ppdbUsers->get();
        foreach ($this->collections as $user) {
            $units[$user->unit_id] = $user->unit_id;
        }
        if (count($units)) {
            $this->units = Unit::whereIn('id', $units)->get();
            if (!$this->units->isEmpty()) {
                foreach ($this->units as $unit) {
                    $this->additionalFields = $this->additionalFields->merge(InputCollectionHelper::additionalData($unit));
                    $this->uploadFields = $this->uploadFields->merge(InputCollectionHelper::uploads($unit));
                }
            }
        }
    }

    // Turn numericalColumns to string datatype
    // Reference => https://docs.laravel-excel.com/3.1/imports/custom-formatting-values.html
    public function bindValue(Cell $cell, $value)
    {
        $numericalColumns = [
            'AT',   // Father's phone number
            'BF',   // Mother's phone number
            'H',    // NIK siswa
            'I' ,   // NIK Ortu
            'BP',   // FATHER / WALI PHONE
            'CB',   //MOTHER PHONE
            'AR',
            'AS',
            'BC',
            'BO'
        ];
        if (in_array($cell->getColumn(), $numericalColumns)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        return $this->collections;
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($PPDBUser): array
    {
        $father = $PPDBUser->father();
        $mother = $PPDBUser->mother();
        $wali = $PPDBUser->wali();

        if ($PPDBUser->payment_option == 'BCA'){
            $maps = [
                $PPDBUser->register_number,
                $PPDBUser->isEmailVerified ? 'Terverifikasi' : 'Belum Terverifikasi',
                $PPDBUser->IsPaymentStatusVerifiedBca ? 'Terverifikasi' : 'Belum Terverifikasi',
                $PPDBUser->isDataComplete ? 'Lengkap' : 'Belum Lengkap',
                $PPDBUser->isParentsComplete ? 'Lengkap' : 'Belum Lengkap',
                @$PPDBUser->unit->name,
                $PPDBUser->name,
                $PPDBUser->nik_siswa,
                $PPDBUser->nik_ortu,
                $PPDBUser->user->email,
                $PPDBUser->origin_school,
                $PPDBUser->user->username,
                $PPDBUser->gender,
                $PPDBUser->place_of_birth,
                $PPDBUser->date_of_birth,
                $PPDBUser->address,
                $PPDBUser->city,
                $PPDBUser->region,
                $PPDBUser->country,
                $PPDBUser->religion,
                $PPDBUser->school_year,
            ];
        }else{
            $maps = [
                $PPDBUser->register_number,
                $PPDBUser->isEmailVerified ? 'Terverifikasi' : 'Belum Terverifikasi',
                $PPDBUser->isPaymentStatusVerified ? 'Terverifikasi' : 'Belum Terverifikasi',
                $PPDBUser->isDataComplete ? 'Lengkap' : 'Belum Lengkap',
                $PPDBUser->isParentsComplete ? 'Lengkap' : 'Belum Lengkap',
                @$PPDBUser->unit->name,
                $PPDBUser->name,
                $PPDBUser->nik_siswa,
                $PPDBUser->nik_ortu,
                $PPDBUser->user->email,
                $PPDBUser->origin_school,
                $PPDBUser->user->username,
                $PPDBUser->gender,
                $PPDBUser->place_of_birth,
                $PPDBUser->date_of_birth,
                $PPDBUser->address,
                $PPDBUser->city,
                $PPDBUser->region,
                $PPDBUser->country,
                $PPDBUser->religion,
                $PPDBUser->school_year,
            ];
        }

        foreach ($this->additionalFields->all() as $field=>$value) {
            $maps[] = $PPDBUser->$field;
        }

        $maps = array_merge($maps, $PPDBUser->isWaliRequired ? [
            $wali ? $wali->name:null,
            $wali ? $wali->place_of_birth:null,
            $wali ? $wali->date_of_birth:null,
            $wali ? $wali->address:null,
            $wali ? $wali->city:null,
            $wali ? $wali->region:null,
            $wali ? $wali->country:null,
            $wali ? $wali->religion:null,
            $wali ? $wali->phone:null,
            $wali ? $wali->job:null,
            $wali ? $wali->salary:null,
            $wali ? $wali->education:null,

            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
        ] : [
            $father ? $father->name:null,
            $father ? $father->place_of_birth:null,
            $father ? $father->date_of_birth:null,
            $father ? $father->address:null,
            $father ? $father->city:null,
            $father ? $father->region:null,
            $father ? $father->country:null,
            $father ? $father->religion:null,
            $father ? $father->phone:null,
            $father ? $father->job:null,
            $father ? $father->salary:null,
            $father ? $father->education:null,

            $mother ? $mother->name:null,
            $mother ? $mother->place_of_birth:null,
            $mother ? $mother->date_of_birth:null,
            $mother ? $mother->address:null,
            $mother ? $mother->city:null,
            $mother ? $mother->region:null,
            $mother ? $mother->country:null,
            $mother ? $mother->religion:null,
            $mother ? $mother->phone:null,
            $mother ? $mother->job:null,
            $mother ? $mother->salary:null,
            $mother ? $mother->education:null,
        ]);

        foreach ($this->uploadFields->all() as $field=>$value) {
            if ($field === 'report_cards') {
                $reportCardsValue = $PPDBUser->$field;
                if (count($reportCardsValue)) {
                    $maps[] = implode(" , ", collect($reportCardsValue)->map(function($value) {
                        $this->getImageUrl($value);
                    })->all());
                } else {
                    $maps[] = null;
                }

            } else {
                $maps[] = $PPDBUser->$field ? $this->getImageUrl($PPDBUser->field) : null;
            }
        }

        $maps = array_merge($maps, [
            $PPDBUser->origin_school,
            $PPDBUser->created_at
        ]);

        return $maps;
    }

    public function headings(): array
    {
        $headings = [
            'REGISTER NUMBER',
            'EMAIL TERVERIFIKASI',
            'PEMBAYARAN TERVERIFIKASI',
            'DATA SISWA LENGKAP',
            'DATA ORANG TUA LENGKAP',
            'DAFTAR PADA UNIT',
            'NAME',
            'NIK SISWA',
            'NIK ORANGTUA',
            'EMAIL',
            'ORIGIN SCHOOL',
            'USERNAME',
            'GENDER',
            'PLACE OF BIRTH',
            'DATE OF BIRTH',
            'ADDRESS',
            'CITY',
            'REGION',
            'COUNTRY',
            'RELIGION',
            'SCHOOL YEAR',
        ];

        $headings = array_merge($headings, $this->additionalFields->map(function ($value, $index) {
            return strtoupper(str_replace('_', ' ', $index));
        })->all());

        $headings = array_merge($headings, [
            'FATHER / WALI NAME',
            'FATHER / WALI PLACE OF BIRTH',
            'FATHER / WALI DATE OF BIRTH',
            'FATHER / WALI ADDRESS',
            'FATHER / WALI CITY',
            'FATHER / WALI REGION',
            'FATHER / WALI COUNTRY',
            'FATHER / WALI RELIGION',
            'FATHER / WALI PHONE',
            'FATHER / WALI JOB',
            'FATHER / WALI SALARY',
            'FATHER / WALI EDUCATION',

            'MOTHER NAME',
            'MOTHER PLACE OF BIRTH',
            'MOTHER DATE OF BIRTH',
            'MOTHER ADDRESS',
            'MOTHER CITY',
            'MOTHER REGION',
            'MOTHER COUNTRY',
            'MOTHER RELIGION',
            'MOTHER PHONE',
            'MOTHER JOB',
            'MOTHER SALARY',
            'MOTHER EDUCATION'
        ]);

        $headings = array_merge($headings, $this->uploadFields->map(function ($value, $index) {
            return strtoupper(str_replace('_', ' ', $index));
        })->all());

        // data PPDB
        $headings = array_merge($headings, [
            'SEKOLAH ASAL',
            'TANGGAL DAFTAR'
        ]);

        return $headings;
    }
}
