<?php

namespace App\Imports;

use League\Csv\Reader;
use App\Models\PPDBUser;
use League\Csv\Statement;
use App\Helpers\PriceHelper;
use App\Models\ProductOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CimbUniformPaymentsImport
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

        $VANames = $this->getVANames($csv);

        $collected  = $this->collectOrders($filtered, $VANames);

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

        if (! in_array('Virtual Account Number', $header)) {
            return []; 
        }

        $iterator = $csv->fetchColumn(array_search('Virtual Account Number', $header));
        
        $arr = iterator_to_array($iterator);
        $arr = array_filter($arr, function ($value) {
            return substr($value, 7, 2) == '08';
        });

        $arr = array_map(function ($value) {
            return substr($value, 9, 7);
        }, $arr);

        return $arr;
    }

    private function getVANames($csv)
    {
        $header = $csv->getHeader();

        if (! in_array('Virtual Account Number', $header)) {
            return []; 
        }

        $virtualAccountNumber = $csv->fetchColumn(array_search('Virtual Account Number', $header));
        $virtualAccountName = $csv->fetchColumn(array_search('Virtual Account Name', $header));
        
        $arrVANumber = iterator_to_array($virtualAccountNumber, false);
        $arrVAName = iterator_to_array($virtualAccountName, false);
        
        $arr = [];

        foreach ($arrVANumber as $key => $value) {
            $arr[$key]['number'] = $value;
            $arr[$key]['name'] = $arrVAName[$key];
        }

        return $arr;
    }

    private function collectOrders($filtered, $VANames)
    {
        $productOrders = ProductOrder::with('user.ppdb', 'productOrderDetails.productDetail')->whereHas('user.ppdb', function ($query) use ($filtered) {
            return $query->whereIn('register_number', array_values($filtered));
        })->get()->map(function ($item) {
            $item['register_number'] = $item->user->ppdb->register_number;
            $item['name'] = $item->user->ppdb->name;
            $item['grand_total'] = $item->grand_total;
            return $item;
        });

        $collect = collect();

        if ($productOrders->isNotEmpty()) {
            foreach ($filtered as $key => $registerNumber) {
                $rowNumber = $key + 1;
                $orders = $productOrders->where('register_number', $registerNumber);
                if ($orders->isEmpty()) {
                    $this->failure[] = '[ROW ' . $rowNumber . '] ' . $VANames[$key]['number'] . ' ' . $VANames[$key]['name'] . ', pesanan tidak ditemukan';
                } else {
                    $collect->put($key, [
                        'register_number' => $orders->first()->register_number,
                        'name' => $orders->first()->name,
                        'grand_totals' => $orders->pluck('grand_total')->all()
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
        if (isset($row['Virtual Account Amount']) && (! in_array($row['Virtual Account Amount'], $data['grand_totals']))) {
            $this->failure[] = '[ROW ' . $rowNumber . '] ' . $data['register_number'] . ' ' . $data['name'] . ' nominal tidak sesuai '; // . $row['Virtual Account Amount'] . ' nominal seharusnya ' .  implode(',',$data['grand_totals']);
            return null;
        }

        return Validator::make($row, $this->rules())->validate();
    }

    private function processData($params, $rowNumber, $data)
    {
        try {
            $this->collection->put($data['register_number'] . '-' . $params['Virtual Account Amount'], $params);
            $this->success[] = '[ROW ' . $rowNumber . '] ' . $data['register_number'] . ' ' . $data['name'];
        } catch (\Exception $e) {
            $this->failure[] = '[ROW ' . $rowNumber . ']' . $e->getMessage();
        }
    }

    private function rules() : array 
    {
        return [
            'Virtual Account Number' => 'required',
            'Posting Date' => 'required|date',
            'Virtual Account Name' => 'required',
            'Virtual Account Amount' => 'required',
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
