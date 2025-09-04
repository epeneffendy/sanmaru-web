<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Services\TeacherService;
use App\Services\UserService;
use App\Models\User;

class TeachersImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(UserService $userService, TeacherService $teacherService)
    {
        $this->userService = $userService;
        $this->teacherService = $teacherService;
    }

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
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
        return 1000;
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
            return;
        }
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = '[ROW ' . ($rowNumber) . '] nik ' . $params['nik'] . ' gagal upload';
        }
    }

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            $this->teacherService->updateByNik($params);
        } else {
            $this->userService->register(User::TEACHER, $params);
        }
    }

    private function validateParams(Collection $row)
    {
        $params = Validator::make($row->toArray(), $this->rules($row['nik']))->validate();
        return $params;
    }

    private function rules($nik){
        return [
            'nik' => ['required', "unique:teachers,nik,{$nik},nik"],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', "unique:teachers,email,{$nik},nik"],
            'mobile_phone' => ['required', 'phone:ID,mobile', "unique:teachers,mobile_phone,{$nik},nik"],
            'address' => ['nullable']
        ];
    }
}
