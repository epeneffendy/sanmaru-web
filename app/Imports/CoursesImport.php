<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use App\Http\Requests\CourseImportRequest;
use App\Models\Course;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\CourseService;

class CoursesImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        $units = Unit::all('id', 'name');
        foreach ($rows as $key => $row) {
            $unit = $units->firstWhere('name', $row['unit_name']);
            $rowNumber = $key+2;
            if(!$unit) {
                $this->failure[$key] = '[ROW '. ($rowNumber) .'] Unit tidak ditemukan';
                continue;
            } else {
                $row['unit_id'] = $unit->id;
            }
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
            print_r($params);
            try {
                DB::beginTransaction();
                $this->storeOrUpdate($params, new CourseService);
                $this->success[] = $params;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->failure[$key] = '[ROW '. ($rowNumber) .'] course "'.$params['name'].'" gagal upload. ';
            }
        }
    }

    private function storeOrUpdate($params, CourseService $courseService)
    {
        if ($this->overwrite) {
            $course = Course::where('code', $params['code'])->first();
            $courseService->update($course->id, $params);
        } else {
            $courseService->create($params);
        }
    }

    private function fillParams(Collection $row)
    {
        $courseImportRequest = new CourseImportRequest($row['code']);
        return Validator::make($row->toArray(), $courseImportRequest->rules())->validated();;
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
}
