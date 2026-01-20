<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\ProductHelper;
use App\Http\Requests\ComplaintOrderRequest;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ComplaintCategory;
use App\Models\ComplaintOrders;
use App\Models\ComplaintPeriode;
use App\Models\PPDBUser;
use App\Models\Product;
use App\Models\ProductFitting;
use App\Models\ProductOrder;
use App\Models\ProductOrderComplaint;
use App\Models\ProductOrderDetail;
use App\Models\ProductUserFitting;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\CartService;
use App\Services\ComplaintOrderService;
use App\Services\ProductOrderComplaintService;
use App\Services\ProductOrderService;
use App\Services\VoucherService;
use App\Traits\ImageHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

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

        $orders = ProductOrder::where('status', ProductOrder::STATUS_NEW_ORDER)
            ->whereNull('payment_image')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($orders as $order) {
            if (Carbon::now()->format('Y-m-d H:i:s') > $order->getExpiredAtAttribute()->toDateTimeString()) {
                $order->status = ProductOrder::STATUS_CANCEL;
                $order->save();
                if ($order->voucher !== NULL) {
                    VoucherUsage::where('product_order_id', $order->id)
                        ->where('voucher_id', json_decode($order->voucher, TRUE)['id'])
                        ->delete();
                }
            }
        }

        $data = [
            'products' => $products,
            'fittings' => $fittings,
            'user_fittings' => ProductUserFitting::where('user_id', $this->user->id)->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child' => 'Product'],
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
            'nav' => ['parent' => 'product', 'child' => 'Product'],
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
        $now = Carbon::now()->format('Y-m-d');

        $order = ProductOrder::where([
            'id' => $id,
            'user_id' => $this->user->id
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $periodComplaint = ComplaintPeriode::where('type', 'siswa')->first();
        $is_complaint = false;
        if ($periodComplaint->status == 'all') {
            $is_complaint = true;
        } else {
            if (($now >= $periodComplaint->date_start) && ($now <= $periodComplaint->date_end)) {
                $is_complaint = true;
            }
        }

        $data = [
            'order' => $order,
            'user' => $this->student,
            'is_complaint' => $is_complaint,
            'periodComplaint' => $periodComplaint,
            'nav' => ['parent' => 'product', 'child' => 'Pesanan']
        ];

        return view('student-dashboard.shop.order', $data);
    }

    public function embedProductCart(VoucherService $voucherService)
    {
        $this->fillDataVarible();

        $fittings = ProductFitting::where('unit_id', $this->class->unit_id)->with('users')->get();
        $vouchers = Voucher::eligible($this->user->toArray());
        $cart = Cart::where('user_id', $this->user->id)->with('details', 'details.product', 'details.product.details', 'details.product_detail')->first();
        $orders = $this->checkOrders($this->user->id);

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
        $details = CartDetail::where('cart_id', $cart->id)->whereHas('product', function ($query) {
            $query->byType(ProductTypeEnum::SERAGAM);
        })->get();

        $getVoucher = [];

        foreach ($vouchers as $ind => $voucher) {
            $getVoucher[$ind] = $voucherService->getVoucher($voucher);
        }

        $data = [
            'cart' => $cart,
            'details' => $details,
            'fittings' => $fittings,
            'vouchers' => $getVoucher,
            'isCartVoucherFulfilled' => $isCartVoucherFulfilled,
            'cartVoucherProducts' => $cartVoucherProducts,
            'orders' => $orders['orders'],
            'no_invoice' => $orders['no_invoice'],
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
        $user = $request->session()->get('user');
        DB::beginTransaction();
        try {
            $params = $request->validate([
                'products' => 'required',
                'products.*.qty' => ['required', 'numeric', 'min:1'],
                'products.*.price' => ['required', 'numeric'],
                'products.*.id' => ['required', 'exists:cart_details,id'],
                'products.*.include' => ['required', 'in:true,false'],
                'products.*.note' => ['nullable', 'string'],
            ]);

            if ($order = $cartService->store($params, $user)) {
                DB::commit();
                return Response::json([
                    'status' => 'success',
                    'order' => $order->id
                ]);
            }

//            $this->fillDataVarible();

        } catch (ValidationException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'false',
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

    public function checkOrders($user_id)
    {
        $orders = ProductOrder::where(['status' => 'new_order', 'user_id' => $user_id])->get();

        $no_invoice = '';
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $no_invoice .= $order->invoice_no . ', ';
            }
            $no_invoice = substr($no_invoice, 0, -2);
        }

        return ['orders' => count($orders), 'no_invoice' => $no_invoice];
    }

    public function postCancelOrder(Request $request, ProductOrderService $productOrderService)
    {
        $user = $request->session()->get('user');

        $params = $request->validate([
            'product_order_id' => 'required|exists:product_orders,id',
            'payment_cancel_reason' => 'required|string'
        ]);

        if ($productOrderService->cancel($params, $user, 2)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function complaint(Request $request)
    {
        $productOrder = ProductOrder::whereId($request->id)->first();

        $historyComplaint = ComplaintOrders::where('product_order_id', $productOrder->id)->orderBy('id','desc')->get();

        $products = [];
        foreach ($productOrder->productOrderDetails as $item) {
            $products[$item->id] = $item->product->name . ' (Size : '. $item->productDetail->size.')';
        }

        $complaintCategory = ComplaintCategory::where('status', 1)->get();

        $data = [
            'productOrder' => $productOrder,
            'products' => $products,
            'historyComplaint' => $historyComplaint,
            'complaintCategory' => $complaintCategory
        ];
        return view('student-dashboard.shop.complaint', $data);
    }

    public function fetchProductOrder(Request $request)
    {
        $productOrderDetail = ProductOrderDetail::whereId($request->id)->first();

        $html = '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Qty : ' . $productOrderDetail->quantity . '</div>';
        $html .= '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Size : ' . $productOrderDetail->productDetail->size . '</div>';
        $html .= '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Note : ' . $productOrderDetail->note . '</div><br><br>';

        return $html;
    }

    public function complaintStore(ComplaintOrderRequest $request, ProductOrderComplaintService $productOrderComplaintService)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validated();

            $productOrderDetail = ProductOrderDetail::whereId($request->product_id)->first();

            if (isset($productOrderDetail)) {
                $dataCompaint = ComplaintOrders::where([
                    'product_order_id'=>$request->product_order_id,
                    'product_order_detail_id' =>$request->product_id
                ])->first();

                $is_complaint = false;
                if (empty($dataCompaint)) {
                    $is_complaint = true;
                }else{
                    if($dataCompaint->status == 'cancel'){
                        $is_complaint = true;
                    }
                }

                if ($is_complaint) {
                    $payload = [
                        'product_order_id' => $productOrderDetail->product_order_id,
                        'product_id' => $productOrderDetail->product_id,
                        'product_detail_id' => $productOrderDetail->product_detail_id,
                        'user_id' => Auth::id(),
                        'type'=>'siswa'
                    ];

                    $store = $productOrderComplaintService->store($request->all(), $payload);

                    if ($store['success'] == true) {
                        DB::commit();
                        return redirect()->route('embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => 'Komplain sudah terkirim, silahkan tunggu konfirmasi admin', 'success' => true]);
                    } else {
                        DB::rollBack();
                        return redirect()->route('embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => $store['message'], 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                    }

                } else {
                    return redirect()->route('embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => 'Anda telah mengajukan komplain untuk product ini!', 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
//            return redirect()->route('admin.product-acceptance.index')->with('errors', collect(['Gagal ditambahkan']));
        }
    }

    public function cancelComplaint(Request $request, ComplaintOrderService $complaintOrderService)
    {
        $data = ComplaintOrders::whereId($request->id)->first();

        $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_CANCEL, '');
        return redirect()->route('embed-product.complaint', ['id' => $data->productOrderDetail->product_order_id]);
    }

    public function showComplaintPdf(Request $request, $id)
    {
        $this->fillDataVarible();

        $complaintOrder = ComplaintOrders::where([
            'id' => $id,
        ])->firstOrFail();

        $productOrder = ProductOrder::where([
            'id'=>$complaintOrder->product_order_id
        ])->firstOrFail();

        $orderDetail = ProductOrderDetail::where('id',$complaintOrder->product_order_detail_id)->firstOrFail();
        $user = User::where('id',Auth::user()->id)->first();

        $data = [
            'complaintOrder' => $complaintOrder,
            'productOrder'=>$productOrder,
            'orderDetail'=>$orderDetail,
            'user' => $user,
        ];

//        return view('student-dashboard.shop.pdf_complaint', $data);

        $pdf = \PDF::loadView('student-dashboard.shop.pdf_complaint', $data);
        $date_complaint = Carbon::parse($complaintOrder->created_at)->format('Ymd');
        return $pdf->download("detail-complaint-".$date_complaint."-".$complaintOrder->user->name.".pdf");
    }

}
