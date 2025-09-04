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
use App\Services\UnitService;

class UnitsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

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
                $this->failure[$key] = '[ROW '. ($rowNumber) .'] unit "'.$params['name'].'" gagal upload';
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
            $this->unitService->updateByUnitCode($params);
        } else {
            $this->unitService->create($params);
        }
    }

    private function fillParams(Collection $row)
    {
        $params = Validator::make($row->toArray(), $this->rules($row['unit_code']))->validate();
        return $params;
    }

    private function rules($unitCode){
        return [
            'name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'unit_code' => ['required', "unique:units,unit_code,{$unitCode},unit_code"]
        ];
    }
}
