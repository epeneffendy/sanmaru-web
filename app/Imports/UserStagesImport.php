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
use Illuminate\Validation\Rule;
use App\Models\PPDBUserStage;
use App\Models\PPDBUser;
use App\Models\Stage;

class UserStagesImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;
    private $stage;

    private $success = [];
    private $failure = [];

    protected $ppdbUsers;

    public function __construct(Stage $stage)
    {
        $this->stage = $stage;
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
        return Validator::make($row->toArray(), $this->rules())->validate();
    }

    private function rules()
    {
        return [
            'register_number' => [
                'required',
                Rule::in($this->ppdbUsers())
            ], 
            'status' => [
                'nullable',
                Rule::in(['', 'pending', 'tidak lolos', 'lolos'])
            ],
            'keterangan_1' => [
                'nullable',
                'string'
            ],
            'keterangan_2' => [
                'nullable',
                'string'
            ],
            'keterangan_3' => [
                'nullable',
                'string'
            ],
        ];
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            if (!$this->overwrite) {
                PPDBUserStage::where('stage_id', $this->stage->id)->delete();
            }

            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = '[ROW '. ($rowNumber) .'] name '.$params['register_number'].' gagal upload.';
        }
    }

    private function storeOrUpdate($params)
    {
        $ppdbUser = PPDBUser::where('register_number', $params['register_number'])->first();
        $note = $params['keterangan_1'];
        if ($params['keterangan_2']) {
            $note.= PHP_EOL.$params['keterangan_2'];
        }
        if ($params['keterangan_3']) {
            $note.= PHP_EOL.$params['keterangan_3'];
        }

        return PPDBUserStage::updateOrCreate([
            'ppdb_user_id' => $ppdbUser->id,
            'stage_id' => $this->stage->id,
        ], [
            'ppdb_user_id' => $ppdbUser->id,
            'stage_id' => $this->stage->id,
            'passed' => $params['status'] == 'lolos' ? 1  : ($params['status'] == 'tidak lolos' ? 0 : ($params['status'] == 'pending' ? 2 : null)),
            'note' => $params['status'] == 'lolos' ? $note : null
        ]);
    }

    private function ppdbUsers()
    {
        if ($this->ppdbUsers) {
            return $this->ppdbUsers;
        }

        $ppdbUsers = PPDBUser::where('periode', $this->stage->periode)->select('id', 'register_number', 'periode')->get();

        if ($this->stage->is_opening_shop_feature) {
            $accepted = [];
            $development = Stage::where('periode', $this->stage->periode)->where('is_opening_development_feature', 1)->first();
            if ($development) {
                $accepted = PPDBUserStage::where('stage_id', $development->id)
                                ->where('passed', 1)->pluck('ppdb_user_id')->all();
            }

            $ppdbUsers = $ppdbUsers->filter(function ($ppdbUser) use ($accepted) {
                return in_array($ppdbUser->id, $accepted);
            })->values();
        }

        return $this->ppdbUsers = $ppdbUsers->isNotEmpty() ? $ppdbUsers->pluck('register_number')->all() : [];
    }
}
