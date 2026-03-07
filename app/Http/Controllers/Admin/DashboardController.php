<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AnalyticsGetRequest;
use App\Http\Controllers\Controller;
use App\Helpers\AnalyticHelper;
use App\Models\ProductCategory;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use Spatie\Analytics\Period;
use App\Models\Product;
use App\Models\Unit;
use Analytics;

class DashboardController extends Controller
{
    private $page = [
        "parent" => "dashboard",
        "child" => "dashboard"
    ];

    public function index()
    {
        $units = Unit::with(['ppdbUsers' => function ($query) {
            $query->select('unit_id', 'id', 'status', 'payment_form');
        }])->orderBy('unit_code', 'ASC')->get();

        $products = Product::select(['products.name', 'products.type_name', 'product_details.stock', 'product_details.size'])
            ->join('product_details', 'product_details.product_id', '=', 'products.id')
            ->where('product_details.stock', '<', 20)
            ->where('type_name', '!=', 'Kantin')
            ->where('products.deleted_at', '=', null)
            ->where('product_details.deleted_at', '=', null)
            ->where('products.status', '=', Product::STATUS_PUBLISHED)
            ->groupBY('name', 'type_name','size')->get();

        $stock_product = [];
        foreach ($products as $ind => $item) {
            $stock_product[$ind]['text'] = $item->name . ' -- [Ukuran ' .$item->size .'] -- ' . '  Sisa Stock ' . $item->stock;
            $stock_product[$ind]['stock'] = $item->stock;
        }
        $params = [
            'data' => $units,
            'nav' => $this->page,
            'stock' => $stock_product
        ];

        return view('administrator/dashboard', $params);
    }

    public function analytics(AnalyticsGetRequest $request)
    {
        // $date = $request->input('date', date('Y-m-d'));
        // $config = config('analytics');
        // $startDate = Carbon::parse($date)->subDays(56);
        // $endDate = Carbon::parse($date);

        // // week
        // $startWeekDate = Carbon::parse($date)->subWeek();
        // $endWeekDate = Carbon::parse($date);

        // // last Week
        // $startLastWeekDate = Carbon::parse($date)->subDays(14);
        // $endLastWeekDate = Carbon::parse($date)->subDays(8);

        // // this month
        // $startMonthDate = Carbon::parse($date)->subDays(28);
        // $endMonthDate = Carbon::parse($date);

        // $startLastMonth = Carbon::parse($date)->subDays(56);
        // $endLastMonth = Carbon::parse($date)->subDays(29);

        // $period = Period::create($startDate, $endDate);

        // $analytics = Analytics::performQuery(
        //     $period,
        //     $config['ga_metrics'],
        //     [
        //         'dimensions' => $config['ga_dimension']
        //     ]
        // );

        // $column = ['today', 'yesterday', 'this_week', 'last_week', 'this_month', 'last_month'];

        // // referrer
        // // source_medium
        // // device_category
        // // date
        // // channel
        // // users
        // // new_users
        // // old_users
        // $datas = [];
        // foreach ($analytics as $analytic) {
        //     $date = Carbon::parse($analytic[3]);
        //     $keys = [];

        //     if ($date->isSameDay($endDate)) $keys[] = 'today';
        //     if ($date->isYesterday($endDate)) $keys[] = 'yesterday';
        //     if ($date->between($startWeekDate, $endWeekDate)) $keys[] = 'this_week';
        //     if ($date->between($startLastWeekDate, $endLastWeekDate)) $keys[] = 'last_week';
        //     if ($date->between($startMonthDate, $endMonthDate)) $keys[] = 'this_month';
        //     if ($date->between($startLastMonth, $endLastMonth)) $keys[] = 'last_month';

        //     foreach ($keys as $key) {
        //         $datas[$key]['users'] = isset($datas[$key]['users']) ? $datas[$key]['users'] + $analytic[5] : $analytic[5];
        //         $datas[$key]['new_users'] = isset($datas[$key]['new_users']) ? $datas[$key]['new_users'] + $analytic[6] : $analytic[6];
        //         $datas[$key]['old_users'] = $datas[$key]['users'] - $datas[$key]['new_users'];

        //         $datas[$key]['device_category'][$analytic[2]] = isset($datas[$key]['device_category'][$analytic[2]]) ? $datas[$key]['device_category'][$analytic[2]] + $analytic[5] : $analytic[5];
        //     }

        //     $datas['referrer'][$analytic[0]][$analytic[3]] = isset($datas['referrer'][$analytic[0]][$analytic[3]]) ? $datas['referrer'][$analytic[0]][$analytic[3]] + $analytic[5] : $analytic[5];
        //     $datas['source_medium'][$analytic[1]][$analytic[3]] = isset($datas['source_medium'][$analytic[1]][$analytic[3]]) ? $datas['source_medium'][$analytic[1]][$analytic[3]] + $analytic[5] : $analytic[5];
        //     $datas['channel'][$analytic[4]][$analytic[3]] = isset($datas['channel'][$analytic[4]][$analytic[3]]) ? $datas['channel'][$analytic[4]][$analytic[3]] + $analytic[5] : $analytic[5];
        // }

        return view('administrator/dashboard-analytics',
            // array_merge($datas,
            [
                'nav' => [
                    "parent" => "konten",
                    "child" => "dashboard-analytic"
                ],
                // 'date' => $endDate
            ]
        // )
        );
    }

    public function order(Request $request)
    {
        $orders = ProductOrder::where('status', '<>', ProductOrder::STATUS_CANCEL)->with('productOrderDetails', 'productOrderDetails.productDetail');
        $outOfStockProducts = Product::select('*')->whereHas('details', function ($query) {
            return $query->where('stock', 0);
        });
        $soldProducts = Product::select('id')->whereRaw('id in (select product_order_details.product_id from product_orders JOIN product_order_details on product_order_details.product_order_id = product_orders.id WHERE product_orders.status <> "' . ProductOrder::STATUS_CANCEL . '")');

        $orders = $orders->whereHas('productOrderDetails.product.productUnits', function ($query) use ($request) {
            $query->byUserRole();
            if ($request->input('unit_id')) {
                $query->where('unit_id', $request->input('unit_id'));
            }
        });
        $outOfStockProducts = $outOfStockProducts->whereHas('productUnits', function ($query) use ($request) {
            $query->byUserRole();
            if ($request->input('unit_id')) {
                $query->where('unit_id', $request->input('unit_id'));
            }
        });
        $soldProducts = $soldProducts->whereHas('productUnits', function ($query) use ($request) {
            $query->byUserRole();
            if ($request->input('unit_id')) {
                $query->where('unit_id', $request->input('unit_id'));
            }
        });

        $orders = $orders->get();
        $outOfStockProducts = $outOfStockProducts->count('id');
        $soldProducts = $soldProducts->count('id');
        $products = Product::whereHas('productUnits', function ($query) {
            $query->byUserRole();
        })->with([
            'details',
            'productUnits.unit' => function ($query) {
                $query->select('id', 'present_color');
            },
        ])
            ->get();

        $params = [
            'orders' => $orders,
            'soldProducts' => $soldProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'products' => $products,
            'units' => Unit::byUserRole()->get(),
            'productCategories' => ProductCategory::all(),
            'nav' => [
                'parent' => 'shop',
                'child' => 'dashboard-order'
            ]
        ];

        return view('administrator/dashboard-order', $params);
    }
}
