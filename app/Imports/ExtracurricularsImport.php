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
use App\Services\ExtracurricularService;
use Illuminate\Support\Arr;
use App\Models\Classes;
use DB;

class ExtracurricularsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(ExtracurricularService $extracurricularService)
    {
        $this->extracurricularService = $extracurricularService;
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
        $params = Validator::make($row->toArray(), $this->rules($row['code']))->validate();
        return $this->replaceUnitClassWithClassId($params);
    }

    private function replaceUnitClassWithClassId($params)
    {
        $params['class_id'] = $this->classId($params['unit_class']);
        return Arr::except($params, 'unit_class');
    }

    private function classId($unitClass)
    {
        return Classes::where('unit_class', $unitClass)->limit(1)->pluck('id')->first();
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = '[ROW '. ($rowNumber) .'] name '.$params['name'].' gagal upload, '. $e->getMessage();
        }
    }

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            $this->extracurricularService->updateByCode($params['code'], $params);
        } else {
            $this->extracurricularService->createOrThrowByCode($params['code'], $params);
        }
    }

    private function rules($code)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'unit_class' => ['required', 'exists:classes,unit_class'],
            'code' => ['required', "unique:extracurriculars,code,{$code},code"],
        ];
    }
}
