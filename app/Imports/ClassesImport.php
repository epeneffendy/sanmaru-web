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
use App\Services\ClassService;
use Illuminate\Support\Arr;
use App\Models\Unit;

class ClassesImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            $rowNumber = $key+2;
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
            $this->failure[$key] = '[ROW '. ($rowNumber) .'] '. $message;
        }
    }

    private function validateParams(Collection $row)
    {
        $params = Validator::make($row->toArray(), $this->rules($row['unit_class']))->validate();
        return $this->replaceUnitNameWithUnitId($params);
    }

    private function rules($unitClass)
    {
        return [
            'unit_name' => ['required', 'exists:units,name'],
            'class_name' => ['required'],
            'unit_class' => ['required', "unique:classes,unit_class,{$unitClass},unit_class"],
        ];
    }

    private function replaceUnitNameWithUnitId($params)
    {
        $params['unit_id'] = $this->unitId($params['unit_name']);
        $params['name'] = $params['class_name'];
        return Arr::except($params, 'unit_name');
    }

    private function unitId($unitName)
    {
        return Unit::where('name', $unitName)->limit(1)->pluck('id')->first();
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = '[ROW '. ($rowNumber) .'] name '.$params['class_name'].' gagal upload.';
        }
    }

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            $this->classService->updateByUnitClass($params['unit_class'], $params);
        } else {
            $this->classService->createOrThrowByUnitClass($params['unit_class'], $params);
        }
    }
}
