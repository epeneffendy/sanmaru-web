<?php

namespace App\Imports;

use App\Models\Finance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class FinanceImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    private $overwrite = false;

    private $finance;

    private $success = [];

    private $failure = [];

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $rowNumber = $key+2;
            try {
                $params = $this->fillParams($row);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $error) {
                    $message = $error[0];
                    break;
                }
                $this->failure[$key] = '[ROW '. ($rowNumber) .'] '. $message;
                continue;
            }

            try {
                $this->storeOrUpdate($params);
                $this->success[] = $params;
            } catch (\Exception $e) {
                $this->failure[$key] = '[ROW '. ($rowNumber) .'] finance "'.$params['name'].'" gagal upload';
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
            $this->finance->update($params);
        } else {
            Finance::create($params);
        }
    }

    private function fillParams(Collection $row)
    {
        $id = $row['code'];

        if ($this->overwrite)
            $this->finance = Finance::where('code', $row['code'])->first();

        if ($this->finance)
            $id = $this->finance->id;

        $params = Validator::make($row->toArray(), $this->rules($id))->validate();
        return $params;
    }

    private function rules($code){
        return [
            'code' => ['required', "unique:finances,code,{$code},id"],
            'name' => ['required', 'string'],
            'nominal_default' => ['required', 'numeric']
        ];
    }
}
