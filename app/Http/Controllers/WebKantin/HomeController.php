<?php
namespace App\Http\Controllers\WebKantin;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Enums\ProductTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Enums\ProductScheduleDayEnum;
use App\Enums\ProductScheduleTypeEnum;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('siswa')->user();
        $readyProducts = Product::whereHas('category', function ($query) {
            $query->where('type', ProductTypeEnum::KANTIN);
        })->whereHas('schedule', function ($query) {
            $query->where('type', ProductScheduleTypeEnum::READY);
            $query->where('status', 'published');
        });

        $preorderProducts = Product::whereHas('category', function ($query) {
            $query->where('type', ProductTypeEnum::KANTIN);
        })->whereHas('schedule', function ($query) {
            $query->where('type', ProductScheduleTypeEnum::PREORDER);
        });

        if ($request->day) {
            $readyProducts->whereHas('schedule', function ($query) use ($request) {
                $query->where('available_on', 'like', "%$request->day%");
            });
        }

        if ($user) {
            $readyProducts->whereHas('productUnits', function ($query) use ($user) {
                if ($user->student->class) {
                    return $query->where('unit_id', $user->student->class->unit->id);
                } else {
                    return $query;
                }
            });
            $preorderProducts->whereHas('productUnits', function ($query) use ($user) {
                if ($user->student->class) {
                    return $query->where('unit_id', $user->student->class->unit->id);
                } else {
                    return $query;
                }
            });
        }

        $data = [
            'readyProducts' => $readyProducts->get(),
            'preorderProducts' => $preorderProducts->get(),
            'params' => $request->only(['day']),
            'days' => ProductScheduleDayEnum::getValues(),
        ];

        return view('webkantin.index', $data);
    }

    public function search()
    {
        return view('webkantin.search');
    }

    public function notFound()
    {
        return view('webkantin.not-found');
    }

    public function fetchProductDetail(Product $product = null)
    {
        if(!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        $data = [
            'product' => $product,
        ];
        $view = view('webkantin._product-detail', $data)->render();
        return response()->json(['html' => $view], 200);
    }
}
