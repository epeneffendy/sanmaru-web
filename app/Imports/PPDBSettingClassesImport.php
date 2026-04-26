<?php

namespace App\Imports;

use App\Models\Classes;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Services\PPDBUserService;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class PPDBSettingClassesImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;
    private $period, $ppdbService;

    private $success = [];
    private $failure = [];

    protected $ppdbUsers;

    public function __construct(Period $period, PPDBUserService $ppdbUserService)
    {
        $this->period = $period->id;
        $this->ppdbService = $ppdbUserService;
    }

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if (!isset($row['register_number']) || empty($row['register_number'])) {
                continue;
            }

            $rowNumber = $key + 2;
            $params = $this->fillParams($row, $key, $rowNumber);
            if ($params === null) continue;

            $this->processData($params, $key, $rowNumber);
        }
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
        return Validator::make($row->toArray(), $this->rules())->validate();
    }

    private function rules()
    {
        return [
            'register_number' => [
                'required',
            ],
            'name' => [
                'required',
            ],
            'unit' => [
                'required',
            ],
            'kelas' => [
                'required',
            ],
            'nisn' => [
                'required',
            ],
        ];
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

    private function processData($params, $key, $rowNumber)
    {
        try {
            if (empty($params['register_number'])) {
                return;
            }

            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->failure[$key] = "[BARIS {$rowNumber}] " . $params['register_number'] . " - " . $errorMessage;
        }
    }

    private function storeOrUpdate($params)
    {
        $ppdbUser = PPDBUser::where('register_number', $params['register_number'])->first();

        if (!$ppdbUser) {
            throw new \Exception("Nomor registrasi " . $params['register_number'] . " tidak ditemukan.");
        }

        $class = Classes::where('name', $params['kelas'])->first();

        if (!$class) {
            throw new \Exception("Kelas '" . $params['kelas'] . "' tidak tersedia di database.");
        }

        $data = [
            'id' => $ppdbUser->id,
            'nis' => $params['nisn'],
            'class_id' => $class->id,
            'unit_id' => $ppdbUser->unit_id,
            'periode' => $ppdbUser->periode
        ];

        $confirm = $this->ppdbService->confirmFromImport($ppdbUser->id, $data);

        return $confirm;
    }

}