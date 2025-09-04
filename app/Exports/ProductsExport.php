<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Product;
use Carbon\Carbon;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    private $isTemplate = false;
    private $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function collection()
    {
        if ($this->isTemplate) {
            return collect();
        }

        if (isset($this->params['date'])) {
            $date = Carbon::parse($this->params['date'])->toDateString();
        } else {
            $date = Carbon::now()->toDateString();
        }

        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $products = Product::query()
        ->with([
            'units', 'type', 'category', 'details', 'details.orderDetails',
            'details.activityLogs' => function ($query) use ($start, $end) {
                return $query->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            }
        ])
        ->withCount('details')
        ->where(function($query) {
            $query = $query->whereHas('productUnits', function($q) {
                $q->byUserRole();
                if (isset($this->params['unit']) && $this->params['unit']) {
                    $q->where('unit_id', $this->params['unit']);
                }
            });

            if (isset($this->params['category']) && $this->params['category']) {
                $query = $query->whereHas('category', function($q) {
                    $q->where('id', $this->params['category']);
                });
            }

            return $query;
        });

        if (isset($this->params['name']) && $this->params['name']) {
            $name = strtolower($this->params['name']);
            $products = $products->whereRaw("LOWER(name) like '%" . $name . "%'");
        }
        $products = $products->get();

        $collect = collect();
        
        foreach ($products as $product) {
            if (count($product->details)) {
                foreach ($product->details as $detail) {
                    $product = clone($product);
                    $product->size = $detail->size;
                    $product->initial_stock = $detail->initial_stock;
                    $product->stock_sold = $detail->stock_sold;
                    $product->available_stock = $detail->available_stock;
                    $product->today_stock_addition = $detail->today_stock_addition;
                    $product->price_siswa = $detail->price_siswa;
                    $product->price_ppdb = $detail->price_ppdb;
                    $product->unit = $product->units->implode('name', ', ');
                    $collect->push($product);
                }
            } else {
                $collect->push($product);
            }
        }

        return $collect;
    }

    public function setTemplate(bool $value)
    {
        $this->isTemplate = $value;
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->slug,
            $product->weight,
            $product->size,
            $product->level,
            $product->merk,
            $product->initial_stock,
            $product->stock_sold,
            $product->available_stock,
            $product->today_stock_addition,
            $product->price_siswa,
            $product->price_ppdb,
            $product->type_name,
            $product->category_name,
            $product->status,
            $product->unit,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        if ($this->isTemplate) {
            return [
                'NAME',
                'SLUG',
                'WEIGHT',
                'SIZE',
                'LEVEL',
                'MERK',
                'STOCK',
                'PRICE_SISWA',
                'PRICE_PPDB',
                'TYPE',
                'CATEGORY',
                'STATUS',
                'UNIT'
            ];
        }

        return [
            'NAME',
            'SLUG',
            'WEIGHT',
            'SIZE',
            'LEVEL',
            'MERK',
            'STOCK_AWAL',
            'STOCK_TERJUAL',
            'STOCK_TERSISA',
            'PENAMBAHAN_STOCK',
            'PRICE_SISWA',
            'PRICE_PPDB',
            'TYPE',
            'CATEGORY',
            'STATUS',
            'UNIT'
        ];
    }
}
