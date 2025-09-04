<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\ProductHelper;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\ProductFitting;
use App\Models\ProductOrder;
use App\Models\ProductUserFitting;
use App\Models\Voucher;
use App\Services\CartService;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ShopController extends Controller
{
    use ImageHandler;

    /**
     * @var \App\Models\User
     * **/
    private $user;

    /**
     * @var \App\Models\Student
     * **/
    private $student;

    /**
     * @var \App\Models\Classes
     * **/
    private $class;

    /**
     * Fill private variable.
     *
     * @return void
     */
    private function fillDataVarible()
    {
        $this->user = Auth::guard('siswa')->user();
        $this->user->loadMissing('ppdb', 'student', 'student.class', 'student.class.unit');
        $this->student = $this->user->student;
        $this->class = $this->student->class;
    }

    public function embedProduct(Request $request)
    {
        $this->fillDataVarible();
        $fittings = ProductFitting::where('unit_id', $this->class->unit_id)->with('users')->get();
        $products = ProductHelper::suitableProducts($this->student, ProductTypeEnum::SERAGAM, [
            'q' => $request->search
        ]);

        $data = [
            'products' => $products,
            'fittings' => $fittings,
            'user_fittings' => ProductUserFitting::where('user_id', $this->user->id)->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child'=>'Product'],
        ];

        return view('student-dashboard.shop.student-embed-product', $data);
    }

    public function embedProductDetail($id)
    {
        $this->fillDataVarible();
        $fittings = ProductFitting::where('unit_id', $this->class->unit_id)->with('users')->get();

        $data = [
            'product' => Product::published()->where('id', $id)->whereHas('productUnits', function ($query) {
                return $query->where('unit_id', $this->class->unit_id);
            })->with('details')->firstOrFail(),
            'fittings' => $fittings,
            'user_fittings' => ProductUserFitting::where('user_id', $this->user->id)->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child'=>'Product'],
        ];

        return view('student-dashboard.shop.detail', $data);
    }

    public function getOrderList()
    {
        $this->fillDataVarible();

        $orders = ProductOrder::where([
            'user_id' => $this->user->id
        ])->with('productOrderDetails', 'productOrderDetails.product')->orderBy('created_at', 'desc')->get();

        $data = [
            'orders' => $orders,
            'nav' => ['parent' => 'product', 'child' => 'Daftar Pesanan']
        ];

        return view('student-dashboard.shop.order-list', $data);
    }

    public function getOrder($id)
    {
        $this->fillDataVarible();

        $order = ProductOrder::where([
            'id' => $id,
            'user_id' => $this->user->id
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'order' => $order,
            'user' => $this->student,
            'nav' => ['parent' => 'product', 'child' => 'Pesanan']
        ];

        return view('student-dashboard.shop.order', $data);
    }

    public function embedProductCart()
    {
        $this->fillDataVarible();

        $fittings = ProductFitting::where('unit_id', $this->class->unit_id)->with('users')->get();
        $vouchers = Voucher::eligible($this->user->toArray());
        $cart = Cart::where('user_id', $this->user->id)->with('details', 'details.product', 'details.product.details', 'details.product_detail')->first();
        if (!$cart) {
            $cart = (new CartService())->create($this->user->id);
        }
        $cart->load('details', 'details.product', 'details.product.details', 'details.product_detail');

        if ($cart && $voucher = json_decode($cart->voucher, TRUE)) {
            if ($updatedVoucher = $vouchers->filter(function ($q) use ($voucher) {
                return $q->code === $voucher['code'];
            })->first()
            ) {
                $updatedVoucher = $updatedVoucher->only(['id', 'code', 'rule', 'note', 'type', 'usage_limit']);
                $cart->voucher = json_encode($updatedVoucher);
            } else {
                $cart->voucher = null;
            }
            $cart->save();
        }

        $isCartVoucherFulfilled = true;
        $cartVoucherProducts = collect();

        if ($cart && $cart->voucher) {
            $voucher = json_decode($cart->voucher, true);
            $voucher = $vouchers->filter(function ($q) use ($voucher) {
                return $q->code === $voucher['code'];
            })->first();

            if ($voucher && $voucher['type'] == 'free_product') {
                $rule = json_decode($voucher['rule'], true);
                $isCartVoucherFulfilled = false;
                if ($count = $cart->details->filter(function ($q) use ($rule) {
                    return in_array($q->product_id, $rule);
                })->count()
                ) {
                    $isCartVoucherFulfilled = ($count === count($rule));
                }

                $cartVoucherProducts = Product::select('id', 'name')->whereIn('id', $rule)->get();
            }
        }
        $details = CartDetail::where('cart_id', $cart->id)->whereHas('product', function($query) {
            $query->byType(ProductTypeEnum::SERAGAM);
        })->get();
        $data = [
            'cart' => $cart,
            'details' => $details,
            'fittings' => $fittings,
            'vouchers' => $vouchers,
            'isCartVoucherFulfilled' => $isCartVoucherFulfilled,
            'cartVoucherProducts' => $cartVoucherProducts,
            'user_fittings' => ProductUserFitting::where('user_id', $this->user->id)->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child' => 'Keranjang Belanja']
        ];

        return view('student-dashboard.shop.cart', $data);
    }

    public function postFitting(Request $request)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'id' => ['required', 'exists:product_fittings,id', new \App\Rules\ProductfittingAvailableRule],
        ]);

        if (ProductUserFitting::create([
            'fitting_id' => $params['id'],
            'user_id' => $this->user->id
        ])
        ) {
            return Response::json(['status' => 'success']);
        }
    }

    public function postProduct(Request $request, CartService $cartService)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'id' => 'required|exists:products,id',
            'detail_id' => 'required|exists:product_details,id',
            'qty' => 'required|numeric',
            'note' => 'nullable|string'
        ]);

        if ($cartService->add($params, $this->user->toArray())) {
            return Response::json(['status' => 'success']);
        }
    }

    public function postCart(Request $request, CartService $cartService)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'products' => 'required',
            'products.*.qty' => ['required', 'numeric', 'min:1'],
            'products.*.price' => ['required', 'numeric'],
            'products.*.id' => ['required', 'exists:cart_details,id'],
            'products.*.include' => ['required', 'in:true,false'],
            'products.*.note' => ['nullable', 'string'],
        ]);

        if ($order = $cartService->store($params, $this->user->toArray())) {
            return Response::json([
                'status' => 'success',
                'order' => $order->id
            ]);
        }
    }

    public function detailVoucher(Request $request)
    {
        $data = Voucher::where('id', $request->id)->first();

        return view('student-dashboard.shop._modal_detail_voucher', ['voucher' => $data]);
    }

    public function postVoucher(Request $request, CartService $cartService)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'voucher' => 'required|string|exists:vouchers,code'
        ]);

        if ($eligible = $cartService->applyVoucher($params, $this->user->toArray())) {
            return Response::json([
                'status' => 'success',
                'voucher' => $eligible
            ]);
        }
    }

    public function cancelOrder(Request $request, ProductOrderService $productOrderService)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'product_order_id' => 'required|exists:product_orders,id'
        ]);

        if ($productOrderService->cancel($params, $this->user->toArray())) {
            return Response::json(['status' => 'success']);
        }
    }

    public function deleteVoucher(CartService $cartService)
    {
        $this->fillDataVarible();

        if ($cartService->deleteVoucher($this->user)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function deleteCart(Request $request, CartService $cartService)
    {
        $this->fillDataVarible();

        $params = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'cart_detail_id' => 'required|exists:cart_details,id'
        ]);

        if ($cartService->delete($params, $this->user->toArray())) {
            return Response::json(['status' => 'success']);
        }
    }

    public function showPdf(Request $request, $id)
    {
        $this->fillDataVarible();

        $productOrder = ProductOrder::where([
            'id' => $id,
            'user_id' => $this->user->id
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'productOrder' => $productOrder,
            'user' => $this->student,
        ];

        $pdf = \PDF::loadView('student-dashboard.shop.pdf', $data);
        return $pdf->download("detail-transaksi-$productOrder->invoice_no.pdf");
    }

    public function uploadOrderConfirmation(Request $request)
    {
        $data = [];
        $type = 'payment_image';
        $this->fillDataVarible();
        try {
            if ($request->hasFile($type)) {
                $upload = $this->doUploadImage($request->file($type), $type);

                $update = array(
                    $type => $upload['path_upload'],
                );

                $order = ProductOrder::where('id', $request->input('id'))->where('user_id', $this->user->id)->firstOrFail();
                $order->update($update);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($upload, 200);
    }

    public function embedDetailPayment($id)
    {
        $this->fillDataVarible();

        $order = ProductOrder::where([
            'id' => $id,
            'user_id' => $this->user->id
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'order' => $order,
            'user' => $this->student,
            'nav' => ['parent' => 'product', 'child' => 'Pesanan']
        ];

        return view('student-dashboard.shop.detail-payment', $data);
    }
}
