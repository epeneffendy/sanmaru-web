<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\PPDBUser;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\CartDetail;
use App\Models\ProductOrder;
use App\Models\VoucherUsage;
use App\Models\ProductDetail;
use App\Mail\OrderConfirmation;
use App\Models\ProductOrderDetail;

class CartService
{
    public function create($userId)
    {
        return Cart::create([
            'user_id' => $userId,
            'status' => 'new_added'
        ]);
    }

    public function add($params, $user)
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $user['id'],
            'status' => 'new_added'
        ]);

        $productDetail = ProductDetail::where([
            'id' => $params['detail_id'],
            'product_id' => $params['id']
        ])->firstOrFail();

        if ($params['qty'] > $productDetail->stock ) {
            return false;
        }

        $detail = CartDetail::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $params['id'],
            'product_detail_id' => $params['detail_id'],
            'note' => $params['note']
        ]);
        $detail->quantity = $detail->quantity ? $detail->quantity + $params['qty'] : $params['qty'];
        if ($user['type'] == 'ppdb') {
            $detail->total_price = $detail->quantity * $productDetail->price_ppdb;
        } else {
            $detail->total_price = $detail->quantity * $productDetail->price_siswa;
        }

        return $detail->save();
    }

    public function delete($params, $user)
    {
        $cart = Cart::where([
            'user_id' => $user['id']
        ])->firstOrFail();

        $detail = CartDetail::where([
            'cart_id' => $params['cart_id'],
            'id' => $params['cart_detail_id']
        ])->first();

        if ($detail && $detail->delete()) {
            return true;
        }

        return false;
    }

    public function deleteVoucher($user)
    {
        $cart = Cart::where([
            'user_id' => $user['id']
        ])->firstOrFail();

        $cart->voucher = null;

        if ($cart->save()) {
            return true;
        }

        return false;
    }

    public function store($params, $user)
    {
        $emailService = new EmailService();
        $coll = collect($params['products'])->sort()->filter(function($product) {
            return $product['include'] === 'true';
        });

        $cart = Cart::where([
            'user_id' => $user['id']
        ])->firstOrfail();

        $details = CartDetail::where('cart_id', $cart->id)->whereIn('id', $coll->pluck('id'))->delete();
        $payment_type = $coll->pluck('payment_type')->all();

        $bank_account = $va_account = '';
        if ($payment_type[0] == '08'){
            $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();
            $bank_account = \App\Helpers\PriceHelper::paymentInfo($ppdb->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'];
            $va_account = \App\Helpers\PriceHelper::virtualAccountNumber($ppdb, true, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL);
        }

        if ($order = (new ProductOrderService)->create([
            'user_id' => $user['id'],
            'user_type' => $user['type'],
            'status' => ProductOrder::STATUS_NEW_ORDER,
            'product_id' => $coll->pluck('product_id')->all(),
            'product_detail_id' => $coll->pluck('product_detail_id')->all(),
            'size' => $coll->pluck('size')->all(),
            'price' => $coll->pluck('price')->all(),
            'qty' => $coll->pluck('qty')->all(),
            'note' => $coll->pluck('note')->all(),
            'invoice_no' => $user['type'] == User::PPDB ? Helper::invoiceNo($user['ppdb']) : Helper::invoiceNo($user['student'], false),
            'voucher' => $cart->voucher,
            'payment_type'=>$payment_type[0],
            'product_order_detail_id' => [],
            'virtual_account_number' => $va_account,
            'payment_option' => $bank_account,
        ])) {
            if ($cart->voucher) {
                $now = date('Y-m-d H:i:s');
                VoucherUsage::insert([
                    'product_order_id' => $order->id,
                    'voucher_id' => json_decode($cart->voucher, TRUE)['id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
            $total_payment  = $order->syncPayment($order->id);
            $template = (new OrderConfirmation($order, $user));
            if (isset($emailService)) {
                $emailService->sendMail($template, $user['email']);
            }

            return $order;
        }
    }

    public function applyVoucher($params, $user)
    {
        $vouchers = Voucher::eligible($user);

        if ($vouchers && $eligible = $vouchers->filter(function($data) use ($params) {
                return trim(strtolower($data->code)) === trim(strtolower($params['voucher']));
            })->first()) {
            $eligible = $eligible->only(['id', 'code', 'rule', 'note', 'type', 'usage_limit']);
            $cart = Cart::firstOrCreate([
                'user_id' => $user['id'],
                'status' => 'new_added',
            ])->fill([
                'voucher' => json_encode($eligible)
            ])->save();

            return $cart;
        }

        return false;
    }

    public function addToCart($params, $user)
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $user['id'],
            'status' => 'new_added'
        ]);

        $product = Product::where('slug', $params['slug'])->first();
        $productDetail = ProductDetail::where([
            ['product_id', $product->id],
            ['size', $params['size']]
        ])->first();

        $cartDetail = CartDetail::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_detail_id' => $productDetail->id
        ]);
        $cartDetail->quantity = $params['quantity'];
        //$cartDetail->total_price = $params['quantity'] * $productDetail->price;
        if ($user['type'] == 'ppdb') {
            $cartDetail->total_price = $params['quantity'] * $productDetail->price_ppdb;
        } else {
            $cartDetail->total_price = $params['quantity'] * $productDetail->price_siswa;
        }
        return $cartDetail->save();
    }

    public function removeFromCart($params, $user)
    {
        $cart = Cart::where([
            'user_id' => $user['id']
        ])->firstOrFail();

        $product = Product::where('slug', $params['slug'])->first();
        $productDetail = ProductDetail::where([
            ['product_id', $product->id],
            ['size', $params['size']]
        ])->first();

        $cartDetail = CartDetail::where([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_detail_id' => $productDetail->id
        ])->firstOrFail();

        return $cartDetail->delete();
    }

    public function updateCart($params, $user)
    {
        $ids = [];

        $cart = Cart::firstOrCreate([
            'user_id' => $user['id'],
            'status' => 'new_added'
        ]);

        foreach ($params['details'] as $detail) {
            $product = Product::where('slug', $detail['slug'])->first();
            $productDetail = ProductDetail::where([
                ['product_id', $product->id],
                ['size', $detail['size']]
            ])->first();

            $cartDetail = CartDetail::firstOrNew([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_detail_id' => $productDetail->id
            ]);
            $cartDetail->quantity = $detail['quantity'];
            //$cartDetail->total_price = $detail['quantity'] * $productDetail->price;
            if ($user['type'] == 'ppdb') {
                $cartDetail->total_price = $detail['quantity'] * $productDetail->price_ppdb;
            } else {
                $cartDetail->total_price = $detail['quantity'] * $productDetail->price_siswa;
            }
            $cartDetail->save();

            $ids[] = $cartDetail->id;
        }

        if (isset($params['voucher'])) {
            $this->applyVoucher($params, $user->toArray());
        }

        CartDetail::where('cart_id', $cart->id)->whereNotIn('id', $ids)->delete();
    }

    public function getCart()
    {
        $cart = Cart::where('user_id', request()->user()->id)->with('details', 'details.product_detail',  'details.product')->firstOrFail()->makeHidden(['id', 'user_id', 'deleted_at', 'created_at', 'updated_at', 'voucher']);

        foreach ($cart->details as $detail) {
            $detail->name = $detail->product->name;
            $detail->slug = $detail->product->slug;
            $detail->size = $detail->product_detail->size;
            $detail->image = $detail->product->image;
        }

        $cart->grand_total = intVal($cart->grand_total);
        $cart->discount_total = intVal($cart->discount_total);
        $cart->grand_total_after_discount = intVal($cart->grand_total_after_discount);

        $cart->vouchers = json_decode($cart->voucher, TRUE);
        $cart->details->makeHidden(['id', 'cart_id', 'product_id', 'product_detail_id', 'deleted_at', 'created_at', 'updated_at', 'product', 'product_detail']);

        return $cart;
    }
}
