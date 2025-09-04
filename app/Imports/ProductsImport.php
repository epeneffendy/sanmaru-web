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
use App\Services\ProductService;
use App\Models\ProductDetail;

class ProductsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    private $overwrite = false;

    private $success = [];
    private $failure = [];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function setOverwrite(bool $value = false)
    {
        $this->overwrite = $value;
    }

    public function collection(Collection $rows)
    {
        $collection = $details = [];
        foreach ($rows as $key => $row) {
            $rowNumber = $key + 2;

            $row['units'] = [];
            if (isset($row['unit'])) {
                $row['units'] = array_map('trim', explode(',', $row['unit']));
            }

            $params = $this->fillParams($row, $key, $rowNumber);
            if ($params === null) continue;

            if (!isset($details[$params['slug']]['sizes'])) {
                $details[$params['slug']]['sizes'] = [];
                $details[$params['slug']]['prices_siswa'] = [];
                $details[$params['slug']]['prices_ppdb'] = [];
                $details[$params['slug']]['stocks'] = [];
                $details[$params['slug']]['product_details_ids'] = [];
            }

            $details[$params['slug']]['sizes'][] = $params['size'];
            $details[$params['slug']]['stocks'][] = $params['stock'];
            $details[$params['slug']]['prices_siswa'][] = $params['price_siswa'];
            $details[$params['slug']]['prices_ppdb'][] = $params['price_ppdb'];

            $details[$params['slug']]['product_details_ids'][] = '';

            $params['sizes'] = $details[$params['slug']]['sizes'];
            $params['stocks'] = $details[$params['slug']]['stocks'];
            $params['prices_siswa'] = $details[$params['slug']]['prices_siswa'];
            $params['prices_ppdb'] = $details[$params['slug']]['prices_ppdb'];
            $params['product_details_ids'] = $details[$params['slug']]['product_details_ids'];

            $collection[$params['slug']] = $params;

            unset($collection[$params['slug']]['stock']);
            unset($collection[$params['slug']]['size']);
            unset($collection[$params['slug']]['price_siswa']);
            unset($collection[$params['slug']]['price_ppdb']);
        }

        $i = 0;
        foreach ($collection as $key => $row) {
            $rowNumber = $i + 2;
            $this->processData($row, $i, $rowNumber);
            $i++;
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
            $this->failure[$key] = '[ROW ' . ($rowNumber) . '] ' . $message;
            return;
        }
    }

    private function processData($params, $key, $rowNumber)
    {
        try {
            $this->storeOrUpdate($params);
            $this->success[] = $params;
        } catch (\Exception $e) {
            $this->failure[$key] = 'Product dengan slug ' . $params['slug'] . ' gagal upload';
        }
    }

    private function storeOrUpdate($params)
    {
        if ($this->overwrite) {
            $this->productService->updateBySlug($params['slug'], $params);
        } else {
            $this->productService->create($params);
        }
    }

    private function validateParams(Collection $row)
    {
        $params = Validator::make($row->toArray(), $this->rules($row['slug']), $this->messages())->validate();
        return $params;
    }

    private function rules($slug = null)
    {
        if (!$this->overwrite) {
            $slug = null;
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'size' => ['required'],
            'price_siswa' => ['required', 'numeric'],
            'price_ppdb' => ['required', 'numeric'],
            'level' => ['required'],
            'merk' => ['required'],
            'slug' => ['string', "unique:products,slug,{$slug},slug"],
            'status' => ['required', 'in:published,unpublished'],
            'category' => ['required'],
            'type' => ['required'],
            'stock' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'units' => ['required', 'array'],
            'units.*' => ['exists:units,name'],
        ];
    }

    private function messages()
    {
        return [
            'units.*.exists' => 'unit :input tidak valid',
        ];
    }
}
