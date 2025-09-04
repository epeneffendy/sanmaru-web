<?php

namespace App\Imports;

use League\Csv\Reader;
use App\Models\PPDBUser;
use League\Csv\Statement;
use App\Helpers\PriceHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MandiriPaymentsImport
{
    private $collection;
    private $success = [];
    private $failure = [];

    public function __construct()
    {
        $this->collection = collect();
    }

    public function import($path, $disk='default')
    {
        $csv = Reader::createFromPath($this->getPath($path, $disk));
        $csv->setHeaderOffset(0);
        
        $filtered = $this->filterRows($csv);

        if (empty($filtered)) {
            $this->failure[] = 'format file tidak sesuai';
            return;
        }

        $collected  = $this->collectUsers($filtered);

        foreach ($collected as $key => $data) {
            $stmt = Statement::create()->offset($key-1)->limit(1);
            $row = $stmt->process($csv)->fetchOne();
            $rowNumber = $key + 1;
            $params = $this->fillParams($row, $rowNumber, $data);
            if ($params === null) continue;

            $this->processData($params, $rowNumber, $data);
        }
    }

    private function filterRows($csv)
    {
        $header = $csv->getHeader();

        if (! in_array('NIS', $header)) {
            return []; 
        }

        $iterator = $csv->fetchColumn(array_search('NIS', $header));
        $arr = iterator_to_array($iterator);
        $arr = array_filter($arr, function ($value) {
            return substr($value, 7, 2) == '07';
        });

        $arr = array_map(function ($value) {
            return substr($value, 9, 7);
        }, $arr);

        return $arr;
    }

    private function collectUsers($filtered)
    {
        $ppdbUsers = PPDBUser::with('unit', 'period')->whereIn('register_number', array_values($filtered))
            ->get()->keyBy('register_number');

        $collect = collect();

        if ($ppdbUsers->isNotEmpty()) {
            foreach ($filtered as $key => $registerNumber) {
                $rowNumber = $key + 1;
                $ppdbUser = $ppdbUsers->get($registerNumber);
                if (! $ppdbUser) {
                    $this->failure[] = '[ROW ' . $rowNumber . '] ' . 'NIS tidak valid.';
                } else {
                    $collect->put($key, [
                        'register_number' => $ppdbUser->register_number,
                        'name' => $ppdbUser->name,
                        'nominal' => PriceHelper::registration($ppdbUser),
                    ]);
                }
            }
        }

        return $collect;
    }

    private function getPath($path, $disk='default')
    {
        return Storage::disk($disk)->path($path);
    }

    private function fillParams($row, $rowNumber, $data) 
    {
        try {
            return $this->validateParams($row, $rowNumber, $data);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $error) {
                $message = $error[0];
                break;
            }
            $this->failure[] = '[ROW ' . $rowNumber . '] ' . $data['register_number'] . ' ' . $data['name'] . $e->getMessage();
        }
    }

    private function validateParams($row, $rowNumber, $data)
    {
        if (isset($row['Nominal Pembayaran']) && ($row['Nominal Pembayaran'] <> $data['nominal'])) {
            $this->failure[] = '[ROW ' . $rowNumber . '] ' . $data['register_number'] . ' ' . $data['name'] . ' nominal tidak sesuai ' . $row['Nominal Pembayaran'] . ' seharusnya ' . $data['nominal'];
            return null;
        }

        return Validator::make($row, $this->rules())->validate();
    }

    private function processData($params, $rowNumber, $data)
    {
        try {
            $this->collection->put($data['register_number'], $params);
            $this->success[] = '[ROW ' . $rowNumber . '] ' . $data['register_number'] . ' ' . $data['name'];
        } catch (\Exception $e) {
            $this->failure[] = '[ROW ' . $rowNumber . ']' . $e->getMessage();
        }
    }

    private function rules() : array 
    {
        return [
            'NIS' => 'required',
            'Tanggal Pembayaran' => 'required|date',
            'Nama' => 'required',
            'Nominal Pembayaran' => 'required',
        ];
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function getReport()
    {
        return [
            'success' => $this->success,
            'failure' => $this->failure,
        ];
    }
}
