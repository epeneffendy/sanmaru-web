<?php

namespace App\Imports;

use App\Models\Classes;
use App\Models\PPDBUser;
use App\Models\Student;
use App\Models\User;
use App\Services\PPDBUserService;
use App\Services\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PPDBImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError, WithBatchInserts
{
    use Importable, SkipsErrors;

    private $defaultPassword = 'sanmar12345';

    private $success = [];
    private $failure = [];

    private $userService, $PPDBUserService;

    public function __construct(UserService $userService, PPDBUserService $PPDBUserService)
    {
        $this->userService = $userService;
        $this->ppdbUserService = $PPDBUserService;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows->where('', '==', '') as $key => $row) {
            $rowNumber = $key + 2;
            $params = $this->fillParams($row, $key, $rowNumber);
            if ($params === null) continue;

            $this->processData($params, $key, $rowNumber);
        }
    }

    public function getReport()
    {
        return [
            'success' => $this->success,
            'failure' => $this->failure
        ];
    }

    public function chunkSize(): int
    {
        return 150;
    }

    public function batchSize(): int
    {
        return 150;
    }

    private function fillParams(Collection $row, $key, $rowNumber)
    {
        try {
            return $this->validateParams($row);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $message = $error[0];
                break;
            }
            $this->failure[$key] = '[ROW ' . ($rowNumber) . '] ' . $message;
        }
    }

    private function validateParams(Collection $row)
    {
        if (isset($row['date_of_birth'])) {
            try {
                $row['date_of_birth'] = (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth']))->format('Y-m-d');
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $params = Validator::make($row->toArray(), $this->rules($row['nis']))->validate();
        $params = $this->replaceClassNameWithClassId($params);
        return $this->replaceUnitNameWithClassId($params);
    }

    private function rules($nis)
    {
        return [
            'nis' => ['nullable'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'mobile_phone' => ['required', 'phone:ID,mobile'],
            'school_year' => ['required', 'numeric'],
            'class_name' => ['required', 'exists:classes,name'],
            'gender' => ['nullable', 'in:male,female'],
            'place_of_birth' => ['nullable'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'region' => ['nullable'],
            'country' => ['nullable'],
            'religion' => ['nullable'],
            'register_number' => ['nullable'],
            'status' => ['nullable'],
        ];
    }

    private function replaceClassNameWithClassId($params)
    {
        $params['class_id'] = $this->classId($params['class_name']);
        $params['unit_id'] = $this->unitId($params['class_name']);
        $params['unit_name'] = $this->unitName($params['unit_id']);

        return Arr::except($params, 'class_name');
    }

    private function replaceUnitNameWithClassId($params)
    {
        $params['unit_name'] = $this->unitName($params['class_id']);
        return Arr::except($params, 'class_name');
    }

    private function classId($className)
    {
        return Classes::where('name', $className)->limit(1)->pluck('id')->first();
    }

    private function unitId($className)
    {
        return Classes::where('name', $className)->limit(1)->pluck('unit_id')->first();
    }

    private function unitName($classId)
    {
        return Classes::where('id', $classId)->limit(1)->pluck('unit_class')->first();
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            $ppdbUser = PPDBUser::where('register_number','=', $params['register_number'])->first();
            $params = [
                'id'=>$ppdbUser->id,
                'nis'=>$params['nis'],
                'class_id'=>$params['class_id'],
                'unit_id'=>$params['unit_id'],
                'periode'=>$ppdbUser->periode
            ];

            $this->storeOrUpdate($ppdbUser->id,$params);
            $this->success[] = $params;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->failure[$key] = '[ROW ' . ($rowNumber) . '] data student dengan nis ' . $params['nis'] . ' - ' . $params['name'] . ', unit ' . $params['unit_name'] . ' tidak ditemukan, Silahkan melakukan tambah data';
        } catch (\Exception $e) {
            $additionalInfo = '';
            if (isset($e->additionalInfo)) {
                $additionalInfo = $e->additionalInfo;
            }
            if (!empty($additionalInfo)) {
                if (isset($params['nis'])) {
                    $this->failure[$key] = '[ROW ' . ($rowNumber) . '] nis ' . $params['nis'] . ' - ' . $params['name'] . ', unit ' . $params['unit_name'] . ' gagal upload. ' . $additionalInfo;
                } else {
                    $this->failure[$key] = '[ROW ' . ($rowNumber) . '] register number ' . $params['register_number'] . ' - ' . $params['name'] . ', unit ' . $params['unit_name'] . ' gagal upload. ' . $additionalInfo;
                }
            }
        }
    }

    // https://aimsis.atlassian.net/browse/AIMSIS-10542
    // Data student yg kena soft-delete harus direstore dulu
    // Dan default password harus diganti in the future
    // Kalo bisa dengan tanggal lahir
    private function storeOrUpdate($id,$params)
    {
//        $acc = $this->getDeleted($params);
        // set boolean if user or student is deleted
        $isUserDeleted = isset($acc['user']);
        $isStudentDeleted = isset($acc['student']);
        $this->ppdbUserService->confirm($id, $params);

    }

    private function getDeleted($params)
    {
        $res['student'] = Student::onlyTrashed()->where([
            'nis' => $params['nis'],
            'class_id' => $params['class_id']
        ])->first();
        $res['user'] = User::onlyTrashed()->where([
            'email' => $params['email'],
        ])->first();

        return $res;
    }

    private function validateOverwrite($params)
    {
        $overwrite = false;
        $student = Student::where(['nis' => $params['nis'], 'class_id' => $params['class_id']])->first();
        if (isset($student)) {
            $overwrite = false;
            $unitId = $student->class->unit_id;
            if ($unitId == $params['unit_id']) {
                $overwrite = true;
            }
        }
        return $overwrite;
    }
}
