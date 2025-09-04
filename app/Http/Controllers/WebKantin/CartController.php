<?php
namespace App\Http\Controllers\WebKantin;

use App\Enums\ProductScheduleTypeEnum;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\ProductOrderPickupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CartController extends Controller
{
    function __construct()
    {
        $this->middleware('siswa');
    }

    public function index($type = ProductScheduleTypeEnum::READY)
    {
        $user = Auth::guard('siswa')->user();
        $cart = Cart::where('user_id', $user->id)->with(['details', 'details.product', 'details.product_detail'])->first();
        $details = null;
        if ($cart) {
            $details = $cart->details()->whereHas('product', function($query) use ($type) {
                $query->byType('kantin')->whereHas('schedule', function($query) use ($type) {
                    $query->where('type', $type);
                });
            })->get();
        }
        $data = [
            'cart' => $cart,
            'details' => $details,
            'type' => $type,
        ];
        return view('webkantin.cart', $data);
    }

    public function add(Request $request, CartService $cartService)
    {
        $user = Auth::guard('siswa')->user();
        $cart = Cart::where('user_id', $user->id)->with('details')->first();
        $preorderDetail = null;
        if ($cart) {
            $preorderDetail = $cart->details()->whereHas('product', function ($query) {
                $query->byType('kantin')->whereHas('schedule', function ($query) {
                    $query->where('type', ProductScheduleTypeEnum::PREORDER);
                });
            })->with(['product', 'product.schedule'])->first();
        }
        if ($preorderDetail) {
            if ($preorderDetail->product->id != $request->id) {
                $alert = [
                    'title' => 'Keranjang Sudah Terisi',
                    'icon' => 'warning',
                    'text' => 'Kamu sudah memiliki produk pre-order di dalam keranjangmu. Silahkan checkout atau tambahkan produk dengan varian yang sama'
                ];
                session(['alert' => $alert]);
                return redirect(route('kantin.cart.index', ['type' => ProductScheduleTypeEnum::PREORDER]));
            }
        }
        if($cartService->add($request->all(), $user) == false) {
            $alert = [
                'title' => 'Stok Tidak Cukup',
                'icon' => 'warning',
                'text' => 'Stok tidak mencukupi permintaanmu. Mohon kurangi'
            ];
            session(['alert' => $alert]);
            return redirect(route('kantin.cart.index'));
        }

        return redirect(route('kantin.cart.index', ['type' => $request->type]));
    }

    public function delete(Request $request, CartService $cartService)
    {
        $user = Auth::guard('siswa')->user();
        $params = $request->validate([
            'cart_id' => 'required',
            'cart_detail_id' => 'required|exists:cart_details,id'
        ]);
        if ($cartService->delete($params, $user->toArray())) {
            return Response::json([
                'status' => 'success'
            ]);
        }

    }

    public function checkout(Request $request, CartService $cartService)
    {
        $user = Auth::guard('siswa')->user();
        $user->loadMissing('ppdb', 'student', 'student.class', 'student.class.unit');
        $params = $request->validate([
            'products' => 'required',
            'products.*.qty' => ['required', 'numeric', 'min:1'],
            'products.*.price' => ['required', 'numeric'],
            'products.*.id' => ['required', 'exists:cart_details,id'],
            'products.*.include' => ['required', 'in:true,false'],
            'products.*.note' => ['nullable', 'string'],
        ]);
        if ($order = $cartService->store($params, $user->toArray())) {
            $product = $order->productOrderDetails()->first()->product;
            if ($product->schedule->type == ProductScheduleTypeEnum::PREORDER) {
                $order->update($product->schedule->available_on);
            }
            return Response::json([
                'status' => 'success',
                'order' => $order->id
            ]);
        }
    }
}
