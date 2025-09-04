<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use App\Http\Requests\VendorStoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Models\Vendor;
use App\Services\UserService;
use App\Services\VendorService;

class VendorsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(UserService $userService, VendorService $vendorService)
    {
        $this->userService = $userService;
        $this->vendorService = $vendorService;
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

    private function processData($params, $key, $rowNumber)
    {
        try {
            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = '[ROW ' . ($rowNumber) . '] vendor "' . $params['name'] . '" gagal upload';
        }
    }

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            $this->vendorService->update($params['id']);
        } else {
            $this->userService->register(User::VENDOR, $params);
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
        $params = Validator::make($row->toArray(), $this->rules($row['email']))->validate();
        return $params;
    }

    private function rules($email)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', "unique:users,email,{$email},id"],
            'city' => ['required', 'string'],
            'pic' => ['required', 'string'],
            'mobile_phone' => ['required','phone:ID,mobile', "unique:users,mobile_phone,{$email},id"],
            'nota_number' => ['required'],
            'nota_date' => ['required', 'date']
        ];
    }
}
