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
use App\Models\User;
use App\Services\UserService;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $rowNumber = $key + 2;
            try {
                $params = $this->fillParams($row);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $error) {
                    $message = $error[0];
                    break;
                }
                $this->failure[$key] = '[ROW ' . ($rowNumber) . '] ' . $message;
                continue;
            }

            try {
                $this->storeOrUpdate($params);
                $this->success[] = $params;
            } catch (\Exception $e) {
                $this->failure[$key] = '[ROW ' . ($rowNumber) . '] username ' . $params['username'] . ' gagal upload';
            }
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

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            User::where('username', $params['username'])->firstOrFail()->update($params);
        } else {
            $this->userService->register(User::ADMIN, $params);
        }
    }

    private function fillParams(Collection $row)
    {
        $params = Validator::make($row->toArray(), $this->rules($row['username']))->validate();
        return $params + ['type' => 'admin'];
    }

    private function rules($username)
    {
        return [
            'username' => ['required', 'string', 'max:255', "unique:users,username,{$username},username"],
            'status' => ['required', 'in:active,inactive'],
            'email' => ['required', 'email', 'string', 'max:255', "unique:users,email,{$username},username"],
            'mobile_phone' => ['required', 'phone:ID,mobile', "unique:users,mobile_phone,{$username},username"],
        ];
    }
}
