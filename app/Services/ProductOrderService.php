<?php

namespace App\Services;

use Auth;
use Illuminate\Support\Facades\DB;
use Request;
use App\Lib\DbTrx;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Unit;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Student;
use App\Models\Voucher;
use App\Models\PPDBUser;
use App\Models\CartDetail;
use App\Models\ProductOrder;
use App\Models\VoucherUsage;
use App\Traits\ImageHandler;
use App\Models\ProductDetail;
use App\Enums\ProductTypeEnum;
use App\Helpers\ProductHelper;
use App\Services\EmailService;
use App\Mail\NotificationEmail;
use App\Mail\OrderConfirmation;
use App\Models\ProductCategory;
use App\Mail\PaymentOrderConfirmed;
use App\Notifications\PPDBNotification;
use App\Enums\ProductOrderPaymentTypeEnum;
use App\Notifications\StudentNotification;

class ProductOrderService
{
    use ImageHandler;

    public function filter(array $params, int $paginate_limit = null, array $related = null, $type = null)
    {
        $productOrders = ProductOrder::query();
        if (array_key_exists('search', $params) && array_key_exists('scope', $params) && $params['search']) {
            switch ($params['scope']) {
                case 'student_name':
                    $productOrders->whereHas('user.student', function ($query) use ($params) {
                        $query->where('name', 'like', "%$params[search]%");
                    })->orWhereHas('user.ppdb', function ($query) use ($params) {
                        $query->where('name', 'like', "%$params[search]%");
                    });
                    break;
                case 'register_number':
                    $productOrders->whereHas('user.student', function ($query) use ($params) {
                        $query->where('register_number', 'like', "%$params[search]%");
                    })->orWhereHas('user.ppdb', function ($query) use ($params) {
                        $query->where('register_number', 'like', "%$params[search]%");
                    });
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('status', $params) && $params['status']) {
            switch ($params['status']) {
                case 'payment_not_confirmed':
                    $productOrders->paymentNotConfirmed();
                    break;
                case 'payment_uploaded':
                    $productOrders->paymentUploaded();
                    break;
                case 'payment_confirmed':
                    $productOrders->paymentConfirmed();
                    break;
                case 'cancel':
                    $productOrders->canceled();
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('unit', $params) && $params['unit']) {
            $productOrders->whereHas('user.ppdb', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            })->orWhereHas('user.student.class', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $year = substr($params['year'], 2, 2);
            $productOrders->where('invoice_no', 'like', $year . '%');
        }
        if (array_key_exists('date_range', $params) && $params['date_range']) {
            $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
            $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
            $productOrders->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd);
        }
        if (array_key_exists('pickup_status', $params) && $params['pickup_status']) {
            $productOrders->where('pickup_status', $params['pickup_status']);
        }
        if (array_key_exists('type_voucher', $params) && $params['type_voucher']) {
            $productOrders->where('voucher', 'like', '%' . $params['type_voucher'] . '%');
        }

        if (array_key_exists('type_user', $params) && $params['type_user']) {
            $productOrders->where('user_type', '=',  $params['type_user']);
        }

        if ($related) {
            $productOrders->with($related);
        }

        if (!empty($type)) {
            $productOrders->whereHas('productOrderDetails.product.category', function ($query) use ($params, $type) {
                $query->where(['type' => $type]);
            });
        }

        $productOrders->orderBy('created_at', 'desc');

        if ($paginate_limit) {
            return $productOrders->paginate($paginate_limit);
        } else {
            return $productOrders->get();
        }
    }

    public function getAvailableYears()
    {
        return ProductOrder::distinct()->selectRaw('CONCAT("20", SUBSTRING(invoice_no, 1, 2)) as year')->orderBy('year')->get();
    }

    public function generateIndexData($nav)
    {
        $searchScopes = [
            'username' => 'Username Siswa',
            'register_number' => 'Nomor Registrasi Siswa'
        ];

        $productOrder = ProductOrder::query()
            ->with([
                'productOrderDetails',
                'productOrderDetails.productDetail',
                'user' => function ($query) {
                    return $query->select('id', 'type', 'email');
                },
                'user.ppdb' => function ($query) {
                    return $query->select('id', 'name', 'user_id', 'register_number', 'unit_id');
                },
                'user.ppdb.unit' => function ($query) {
                    return $query->select('id', 'name');
                },
                'user.student',
                'user.student.class',
                'user.student.class.unit' => function ($query) {
                    return $query->select('id', 'name');
                },
            ]);


        $productOrder = $productOrder->whereHas('user', function ($query) {
            return $query->whereHas('ppdb', function ($query) {
                $query = $query->whereHas('unit', function ($query) {
                    $query = $query->byUserRole();
                    if (request()->input('unit')) {
                        $query = $query->where('id', request()->input('unit'));
                    }
                });
                if (request()->input('search') && request()->input('scope')) {
                    $query = $query->where(request()->input('scope'), 'like', '%' . request()->input('search') . '%');
                }
                if (request()->input('period')) {
                    $query = $query->whereRaw("LEFT(`ppdb_users`.`register_number`, 2) = '" . substr(request()->input('period'), -2) . "'");
                }
                return $query;
            })->orWhereHas('student', function ($query) {
                $query = $query->whereHas('class.unit', function ($query) {
                    $query = $query->byUserRole();
                    if (request()->input('unit')) {
                        $query = $query->where('id', request()->input('unit'));
                    }
                    return $query;
                });
                if (request()->input('search') && request()->input('scope')) {
                    if (request()->input('scope') == 'register_number') {
                        //disable
                        $query = $query->whereRaw("0 = 1");
                    } else {
                        $query = $query->where(request()->input('scope'), 'like', '%' . request()->input('search') . '%');
                    }
                }
                if (request()->input('period')) {
                    $query = $query->where('school_year', request()->input('period'));
                }
                return $query;
            });
        });

        if (request()->input('status')) {
            if (request()->input('status') == 'payment_not_confirmed') {
                $productOrder = $productOrder->paymentNotConfirmed();
            }
            if (request()->input('status') == 'payment_uploaded') {
                $productOrder = $productOrder->paymentUploaded();
            }
            if (request()->input('status') == 'payment_confirmed') {
                $productOrder = $productOrder->paymentConfirmed();
            }
            if (request()->input('status') == 'cancel') {
                $productOrder = $productOrder->canceled();
            }
        }

        $productOrder = $productOrder->orderBy('created_at', 'desc')->paginate();

        return [
            'nav' => $nav,
            'units' => Unit::byUserRole()->get(),
            'product_orders' => $productOrder,
            'search_scopes' => $searchScopes,
            'params' => Request::only(['search', 'unit', 'page', 'scope', 'period', 'status'])
        ];
    }

    public function generateAddingData($nav, $type = '')
    {
        $type = $type ?: ProductTypeEnum::SERAGAM;

        $studentList = $this->getStudentList();

        return [
            'studentList' => $studentList,
            'units' => Unit::byUserRole()->get(),
            'nav' => $nav,
            'type' => $type
        ];
    }

    private function params($params)
    {
        if (!isset($params['user_type'])) {
            $user = User::where('id', $params['user_id'])->first()->toArray();
            $params['user_type'] = $user['type'];
        }

        if (isset($params['price_siswa']) && isset($params['price_ppdb'])) {
            if ($params['user_type'] == 'ppdb') {
                $params['price'] = $params['price_ppdb'];
            } else {
                $params['price'] = $params['price_siswa'];
            }
            unset($params['price_ppdb']);
            unset($params['price_siswa']);
        }


        if (isset($params['type_tab'])) {
            $params['payment_type'] = '08';
            unset($params['order_amount']);
            unset($params['total_payment']);
            if ($params['type_tab'] == 'kantin') {
                $params['payment_type'] = '12';
            }

            $price = $this->totalPrice($params['price']);
            if (isset($price)) {
                $params['order_amount'] = $price['order_amount'];
                $params['total_payment'] = $price['total_payment'];
            }
        }
        return $params;
    }

    public function create($params)
    {
        $params = $this->params($params);

        $productOrder = ProductOrder::create($params);
        $productOrder->syncDetails($params);

        return $productOrder;
    }

    public function generateEditableData($id, $nav)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->with('productOrderDetails', 'productOrderDetails.productDetail')->findOrFail($id);

        return [
            'method' => 'edit',
            'productOrder' => $productOrder,
            'studentList' => Student::pluck('name', 'user_id'),
            'productList' => Product::pluck('name', 'id'),
            'orderStatus' => (new ProductOrder())->listOrderStatus(),
            'pickupStatus' => (new ProductOrder())->listPickupStatus(),
            'nav' => $nav
        ];
    }

    public function update($id, $params)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->findOrFail($id);

        $productOrder->update($params);
        $productOrder->syncDetails($params);

        return $productOrder;
    }

    public function delete($id)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->with('productOrderDetails')->findOrFail($id);

        $productOrder->productOrderDetails->each(function ($productOrderDetail) {
            $productOrderDetail->delete();
        });

        return $productOrder->delete();
    }

    public function cancel($params, $user, $type)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->where('id', $params['product_order_id'])
            // ->where('user_id', $user['id'])
            ->where('status', ProductOrder::STATUS_NEW_ORDER)
            ->firstOrFail();

        if ($type == 1) {
            if ($productOrder && $productOrder->update(['status' => ProductOrder::STATUS_CANCEL])) {
                if ($productOrder->voucher) {
                    VoucherUsage::where('product_order_id', $productOrder->id)
                        ->where('voucher_id', json_decode($productOrder->voucher, TRUE)['id'])
                        ->delete();
                }

                return true;
            }
        } else {
            if ($productOrder && $productOrder->update([
                    'status' => ProductOrder::STATUS_CANCEL,
                    'payment_cancel_reason' => $params['payment_cancel_reason'],
                    'payment_cancel_date' => date('Y-m-d H:i:s')
                ])) {
                if ($productOrder->voucher) {
                    VoucherUsage::where('product_order_id', $productOrder->id)
                        ->where('voucher_id', json_decode($productOrder->voucher, TRUE)['id'])
                        ->delete();
                }

                return true;
            }
        }


        return false;
    }

    public function confirmPayment($id, $user)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->where('id', $id)
            //->where('user_id', $user['id'])
            ->where('status', ProductOrder::STATUS_NEW_ORDER)
            ->firstOrFail();

        if ($productOrder->update([
            'status' => ProductOrder::STATUS_CONFIRMED,
            'payment_confirmed_date' => date('Y-m-d H:i:s')
        ])) {
            $this->sendPaymentConfirmedEmail($id);
            return true;
        }
        return false;
    }

    public function rejectPayment($id, $params)
    {
        $productOrder = ProductOrder::where(function ($q) {
            return $q->whereHas('user.ppdb.unit', function ($query) {
                $query->byUserRole();
            })->orWhereHas('user.student.class.unit', function ($query) {
                $query->byUserRole();
            });
        })->where('id', $id)
            //->where('user_id', $user['id'])
            ->where('status', ProductOrder::STATUS_NEW_ORDER)
            ->firstOrFail();

        $productOrder->payment_image = NULL;
        $productOrder->save();

        // Create notification
        $user = $productOrder->user;
        $notification = NULL;
        $params['title'] = "[TOLAK] Bukti Pembayaran Registrasi " . $user->name;
        if ($user->type == User::STUDENT) {
            $user->student->notify(new StudentNotification($params));
            $notification = $user->student->unreadNotifications->first();
        } elseif ($user->type == User::PPDB) {
            $user->ppdb->notify(new PPDBNotification($params));
            $notification = $user->ppdb->unreadNotifications->first();
        } else {
            // notify user
        }

        if (array_key_exists('send_email', $params) && $params['send_email']) {
            $emailService = new EmailService();
            $template = (new NotificationEmail($user, $notification));
            $emailService->sendMail($template, $user->email);

            $notification->sended_email = Carbon::now();
            $notification->save();
        }

        return true;
    }

    public function massSetPaymentVerified($input): bool
    {
        $productOrders = ProductOrder::whereIn('id', array_keys($input['product_orders']))->get();

        DbTrx::useTrx(function () use ($productOrders, $input) {
            foreach ($productOrders as $order) {
                $order->status = ProductOrder::STATUS_CONFIRMED;
                $order->payment_confirmed_date = date('Y-m-d H:i:s');
                $order->save();
                event(new \App\Events\PPDB\FinanceUniformPaymentImported($order, $input['payment_dates'][$order->id]));
                $this->sendPaymentConfirmedEmail($order->id);
            }
        });

        return true;
    }

    public function massSendPaymentConfirmedMails()
    {
        $orders = ProductOrder::where(function ($query) {
            $query->where('payment_confirmed_mail_sent', false)->orWhereNull('payment_confirmed_mail_sent');
        })->where('status', ProductOrder::STATUS_CONFIRMED)->whereHas('user.ppdb.unit', function ($query) {
            $query->byUserRole();
        })->get();

        foreach ($orders as $order) {
            if (!$order->payment_confirmed_date) {
                $order->update([
                    'payment_confirmed_date' => date('Y-m-d H:i:s')
                ]);
            }

            $this->sendPaymentConfirmedEmail($order->id);
        }

        return $orders->count();
    }

    public function sendPaymentConfirmedEmail($id)
    {
        $order = ProductOrder::where('id', $id)->where(function ($query) {
            $query->where('payment_confirmed_mail_sent', false)->orWhereNull('payment_confirmed_mail_sent');
        })->where('status', ProductOrder::STATUS_CONFIRMED)->first();

        if (!$order) {
            return false;
        }

        $user = $order->user;

        $emailService = new EmailService();
        $template = (new PaymentOrderConfirmed($order));
        if (isset($emailService)) {
            $emailService->sendMail($template, $user['email'], [
                'ProductOrder',
                [['id', $id]],
                ['payment_confirmed_mail_sent' => 1]
            ]);
        }
    }

    public function createNewOrder($params, $user)
    {
        $emailService = new EmailService();

        $cart = Cart::where([
            'user_id' => $user['id']
        ])->with('details', 'details.product')->firstOrFail();

        $productIds = $productDetailIds = $sizes = $prices = $qtys = [];

        foreach ($params['details'] as $key => $param) {
            $product = Product::where('slug', $param['slug'])->first();
            $detail = ProductDetail::where('product_id', $product->id)->where('size', $param['size'])->first();

            $productIds[$key] = $product->id;
            $productDetailIds[$key] = $detail->id;
            $sizes[$key] = $detail->size;
            $prices_ppdb[$key] = $detail->price_ppdb;
            $prices_siswa[$key] = $detail->price_siswa;
            $qtys[$key] = $param['quantity'];

            CartDetail::where('cart_id', $cart->id)->where('product_id', $product->id)->where('product_detail_id', $detail->id)->delete();
        }

        if ($order = $this->create([
            'user_id' => $user['id'],
            'status' => ProductOrder::STATUS_NEW_ORDER,
            'product_id' => $productIds,
            'product_detail_id' => $productDetailIds,
            'size' => $sizes,
            'price_siswa' => $prices_siswa,
            'price_ppdb' => $prices_ppdb,
            'qty' => $qtys,
            'invoice_no' => $user['type'] == User::PPDB ? Helper::invoiceNo($user->ppdb) : Helper::invoiceNo($user->student, false),
            'voucher' => $cart->voucher,
            'product_order_detail_id' => []
        ])) {

            $template = (new OrderConfirmation($order, $user->toArray()));
            if (isset($emailService)) {
                $emailService->sendMail($template, $user['email']);
            }

            $this->normalizeOrder($order);

            return $order;
        }

        return false;
    }

    public function getOrders($user)
    {
        $orders = ProductOrder::where([
            'user_id' => $user['id']
        ])->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $this->normalizeOrder($order);
        }

        return $orders;
    }

    public function getOrder($id, $user)
    {
        $order = ProductOrder::where([
            'user_id' => $user['id'],
            'id' => $id
        ])->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            return null;
        }

        $this->normalizeOrder($order);

        return $order;
    }

    public function uploadPaymentImage($id, $user, $params)
    {
        $order = ProductOrder::where([
            'user_id' => $user['id'],
            'id' => $id
        ])->first();

        if (!$order) {
            return null;
        }

        if ($upload = $this->doUploadImage($params['image'], 'payment_image')) {
            $order->payment_image = $upload['path_upload'];
            $order->save();

            return $upload;
        }

        return null;
    }

    private function normalizeOrder($order)
    {
        $order->details = $order->productOrderDetails;
        $order->payment_image = $order->getPaymentImageUrl();

        $order->productOrderDetails->makeHidden([
            'id', 'product_order_id', 'product_id', 'product_detail_id', 'deleted_at', 'created_at', 'updated_at'
        ]);

        foreach ($order->details as $detail) {
            $detail->name = $detail->product->name;
            $detail->slug = $detail->product->slug;
            $detail->price = $detail->price;
            $detail->size = $detail->productDetail->size;
            $detail->image = $detail->product->image;

            $detail->makeHidden(['product', 'productDetail']);
        }

        $order->vouchers = json_decode($order->voucher, TRUE);
        $order->grand_total = intVal($order->grand_total_gross);
        $order->discount_total = intVal($order->discount_total);
        $order->grand_total_after_discount = intVal($order->grand_total);

        $order->makeHidden([
            'user_id',
            'deleted_at',
            // 'created_at',
            'updated_at',
            'voucher',
            'productOrderDetails'
        ]);
    }

    public function getStudentList($unitId = null)
    {
        $studentList = collect();
        $students = Student::with('user', 'class', 'class.unit')->whereHas('class.unit', function ($query) use ($unitId) {
            $query = $query->byUserRole();
            if ($unitId)
                $query = $query->where('unit_id', $unitId);
            return $query;
        })->orderBy('name', 'ASC')->get()->each(function ($student) use ($studentList) {
            $studentList->push(['user_id' => $student->user_id, 'name' => $student->name, 'type' => $student->user->type]);
        });
        $ppdbUsers = PPDBUser::with('user', 'unit')->whereHas('unit', function ($query) use ($unitId) {
            $query = $query->byUserRole();
            if ($unitId)
                $query = $query->where('id', $unitId);
            return $query;
        })->orderBy('name', 'ASC')->get()->each(function ($ppdbUser) use ($studentList) {
            $studentList->push(['user_id' => $ppdbUser->user_id, 'name' => $ppdbUser->register_number . ' - ' . $ppdbUser->name, 'type' => $ppdbUser->user->type]);
        });

        return $studentList;
    }

    private function getStudentVoucher($userId)
    {
        $user = User::where('id', $userId)
            ->where(function ($query) {
                $query->whereHas('ppdb.unit')->orWhereHas('student.class.unit');
            })
            ->with('ppdb', 'ppdb.unit', 'student', 'student.class', 'student.class.unit')
            ->first();

        $collect = collect();

        if (!is_null($user)) {
            $user = $user->toArray();
            $vouchers = Voucher::eligible($user)->each(function ($voucher) use ($collect) {
                $disc_percent = 0;
                $disc_fixed = 0;
                if ($voucher->type == Voucher::TYPE_DISC_PERCENT) {
                    $disc_percent = json_decode($voucher->rule);
                } else if ($voucher->type == Voucher::TYPE_DISC_FIXED) {
                    $disc_fixed = json_decode($voucher->rule);
                }
                $collect->push([
                    'code' => $voucher->code,
                    'type' => $voucher->type,
                    'product' => ($voucher->type == Voucher::TYPE_FREE) ? json_decode($voucher->rule) : 'all',
                    'discount_percent' => $disc_percent,
                    'discount_fixed' => $disc_fixed,
                    'usage_limit' => min(1, $voucher->usage_limit),
                    'note' => ($voucher->type == Voucher::TYPE_FREE)
                        ? "Produk gratis"
                        : (($voucher->type == Voucher::TYPE_DISC_FIXED)
                            ? "Potongan harga Rp. {$voucher->rule}"
                            : "Potongan {$voucher->rule} %")
                ]);
            });
        }
        return $collect;
    }

    public function generateStudentData($userId = null, $type = 'seragam')
    {
        $student = PPDBUser::where('user_id', $userId)->first();
        if (!$student) {
            $student = Student::where('user_id', $userId)->first();
        }

        if (!$student) {
            return null;
        }

        $products = ProductHelper::suitableProducts($student, $type);

        $vouchers = $this->getStudentVoucher($userId);

        return [
            'products' => $products,
            'vouchers' => $vouchers
        ];
    }

    public function createByAdmin($params)
    {
        $emailService = new EmailService();
        $user = User::where(function ($query) {
            $query->whereHas('ppdb.unit')->orWhereHas('student.class.unit');
        })
            ->with('ppdb', 'ppdb.unit', 'student', 'student.class', 'student.class.unit')
            ->where('id', $params['user_id'])->first();

        if (isset($params['voucher_code']) && $params['voucher_code']) {
            $vouchers = Voucher::eligible($user->toArray());

            if ($vouchers && $eligible = $vouchers->filter(function ($data) use ($params) {
                    return trim(strtolower($data->code)) === trim(strtolower($params['voucher_code']));
                })->first()) {
                $eligible = $eligible->only(['id', 'code', 'rule', 'note', 'type', 'usage_limit']);
                $params['voucher'] = json_encode($eligible);
            }
            unset($params['voucher_code']);
        }

        $params['invoice_no'] = $user['type'] == User::PPDB ? Helper::invoiceNo($user->ppdb) : Helper::invoiceNo($user->student, false);

        if ($order = $this->create($params)) {
            if (isset($params['voucher']) && $params['voucher']) {
                $now = date('Y-m-d H:i:s');
                VoucherUsage::insert([
                    'product_order_id' => $order->id,
                    'voucher_id' => json_decode($params['voucher'], TRUE)['id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }

            if (isset($emailService)) {
                $template = (new OrderConfirmation($order, $user->toArray()));
                $emailService->sendMail($template, $user->email);
            }

            return $order;
        }
        return false;
    }

    public function getSummaryProductOrder(array $params = [])
    {
        $query = ProductDetail::with([
            'product',
            'orderDetails',
            'orderDetails.productOrder',
            'orderDetails.productOrder.user',
            'orderDetails.productOrder.user.ppdb',
            'orderDetails.productOrder.user.ppdb.unit',
            'orderDetails.productOrder.user.student',
            'orderDetails.productOrder.user.student.class',
            'orderDetails.productOrder.user.student.class.unit',
        ])
            ->whereHas('orderDetails.productOrder', function ($qOrder) use ($params) {
                $qOrder->whereNotIn('status', [
                    ProductOrder::STATUS_NEW_ORDER,
                    ProductOrder::STATUS_CANCEL,
                ]);

                $qOrder->where('payment_type', '!=', ProductOrderPaymentTypeEnum::KANTIN);

                if (array_key_exists('date_range', $params) && $params['date_range']) {
                    $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
                    $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
                    $qOrder->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd);
                }

                if (array_key_exists('unit', $params) && $params['unit']) {
                    $qOrder->where(function ($q) use ($params) {
                        $q->whereHas('user.ppdb', function ($query) use ($params) {
                            $query->where('unit_id', $params['unit']);
                        })->orWhereHas('user.student.class', function ($query) use ($params) {
                            $query->where('unit_id', $params['unit']);
                        });
                    });
                }

                if (array_key_exists('status_student', $params) && $params['status_student']) {
                    $qOrder->whereHas('user', function ($query) use ($params) {
                        $query->where('type', $params['status_student']);
                    });
                }
            });

        $pages = $query->get();

        $findPPDB = isset($params['find_ppdb']) && $params['find_ppdb'];
        $findRegular = isset($params['find_regular']) && $params['find_regular'];

        $products = collect();
        foreach ($pages as $productDetail) {
            $orderDetailUsers = $productDetail->orderDetails;
            foreach ($orderDetailUsers->groupBy('productOrder.user.type') as $type => $orderDetailType) {
                if ($findPPDB && $type === User::PPDB) {
                    foreach ($orderDetailType->groupBy('productOrder.user.ppdb.unit_id') as $orderDetails) {
                        $productOrder = $orderDetails->first()->productOrder;
                        $user = optional($productOrder)->user;
                        $student = optional($user)->ppdb;
                        $unit = optional($student)->unit;
                        $product = $productDetail->product;

                        $countProductSell = $orderDetails->sum('quantity');
                        $total = $countProductSell * $productDetail->price_siswa;
                        $products->push([
                            'unit_name' => optional($unit)->name,
                            'product_name' => $product->name,
                            'size' => $productDetail->size,
                            'price_vendor' => $productDetail->price_vendor_ppdb,
                            'sell_price' => $productDetail->price_ppdb,
                            'count_product_sell' => $countProductSell,
                            'student_type' => User::PPDB,
                            'total_sell' => $total,
                            'profit' => $total - ($productDetail->price_vendor_ppdb * $countProductSell),
                            'initial_stock' => $productDetail->initial_stock,
                            'available_stock' => $productDetail->available_stock,
                            'product_detail_id' => $productDetail->id,
                        ]);
                    }
                } elseif ($findRegular) {
                    foreach ($orderDetailType->groupBy('productOrder.user.student.class.unit_id') as $orderDetails) {
                        $productOrder = $orderDetails->first()->productOrder;
                        $user = optional($productOrder)->user;
                        $student = optional($user)->student;
                        $class = optional($student)->class;
                        $unit = optional($class)->unit;
                        $product = $productDetail->product;

                        $countProductSell = $orderDetails->sum('quantity');
                        $total = $countProductSell * $productDetail->price_siswa;
                        $products->push([
                            'unit_name' => optional($unit)->name,
                            'product_name' => $product->name,
                            'size' => $productDetail->size,
                            'price_vendor' => $productDetail->price_vendor_regular,
                            'sell_price' => $productDetail->price_siswa,
                            'count_product_sell' => $countProductSell,
                            'student_type' => User::STUDENT,
                            'total_sell' => $total,
                            'profit' => $total - ($productDetail->price_vendor_regular * $countProductSell),
                            'initial_stock' => $productDetail->initial_stock,
                            'available_stock' => $productDetail->available_stock,
                            'product_detail_id' => $productDetail->id,
                        ]);
                    }
                }
            }
        }

        return $products;
    }

    public function generateListData($nav, $request, $productOrderService)
    {
        $collections = collect();
        foreach (ProductTypeEnum::getValues() as $type) {
            $related = [
                'user',
                'user.student',
                'user.student.class',
                'user.student.class.unit',
                'user.ppdb',
                'user.ppdb.unit',
                'productOrderDetails',
                'productOrderDetails.productDetail',
                'productOrderDetails.product.category'
            ];
            $searchScopes = [
                'student_name' => 'Nama Siswa',
                'register_number' => 'Nomor Registrasi Siswa'
            ];

            $productOrders = $this->filter($request->all(), 20, $related, $type);

            $collections->put($type, [
                'product_orders' => $productOrders,
                'categories' => ProductCategory::whereType($type)->get(),
                'units' => Unit::byUserRole()->get()->all(),
                'fragment' => $type,
                'search_scopes' => $searchScopes,
                'years' => $productOrderService->getAvailableYears(),
            ]);
        }
        return [
            'nav' => $nav,
            'collections' => $collections,
            'activeTab' => $request->active_tab ?? 'seragam',
            'user'=> Auth::user(),
            'params' => $request->only(['page', 'search', 'scope', 'status', 'unit', 'year', 'date_range', 'pickup_status', 'type_voucher','type_user']),
        ];
    }

    public function totalPrice($prices)
    {
        $total = 0;
        foreach ($prices as $price) {
            $total += $price;
        }

        $params['order_amount'] = $total;
        $params['total_payment'] = $total;

        return $params;
    }

    public function getSummaryPurchaseOrderByUnit(array $params = [])
    {
        $orders = DB::table('product_orders')
            ->select('units.name as unit_name', 'products.name as product_name', 'product_details.size', 'product_orders.status as payment_status', 'product_orders.pickup_status', DB::raw('sum(product_order_details.quantity) AS qty'))
            ->join('ppdb_users', 'ppdb_users.user_id', '=', 'product_orders.user_id')
            ->join('units', 'units.id', '=', 'ppdb_users.unit_id')
            ->join('product_order_details', 'product_order_details.product_order_id', '=', 'product_orders.id')
            ->join('product_details', 'product_details.id', '=', 'product_order_details.product_detail_id')
            ->join('products', 'products.id', '=', 'product_details.product_id')
            ->where('product_orders.payment_type','=','08');


        if (array_key_exists('unit', $params) && $params['unit']) {
            if ($params['unit'] != 'all') {
                $orders->where('ppdb_users.unit_id','=',$params['unit']);
            }
        }

        if (array_key_exists('payment_status', $params) && $params['payment_status']) {
            if ($params['payment_status'] != 'all') {
                $orders->where('product_orders.status','=',$params['payment_status']);
            }
        }


        if (array_key_exists('pickup_status', $params) && $params['pickup_status']) {
            if ($params['pickup_status'] != 'all') {
                $orders->where('product_orders.pickup_status','=',$params['pickup_status']);
            }
        }

        if (array_key_exists('date_range', $params) && $params['date_range']) {
            $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
            $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
            $orders->where('product_orders.created_at', '>=', $dateStart)->where('product_orders.created_at', '<=', $dateEnd);
        }

        $orders = $orders->groupBy(['ppdb_users.unit_id','products.name','product_details.size','product_orders.status','product_orders.pickup_status'])->get();

        return $orders;

    }

    public function getSummaryPurchaseOrderBySiswa(array $params = [])
    {
        $orders = DB::table('product_orders')
            ->select('ppdb_users.name','units.name as unit_name', 'products.name as product_name', 'product_details.size', 'product_orders.status as payment_status', 'product_orders.pickup_status', DB::raw('sum(product_order_details.quantity) AS qty'))
            ->join('ppdb_users', 'ppdb_users.user_id', '=', 'product_orders.user_id')
            ->join('units', 'units.id', '=', 'ppdb_users.unit_id')
            ->join('product_order_details', 'product_order_details.product_order_id', '=', 'product_orders.id')
            ->join('product_details', 'product_details.id', '=', 'product_order_details.product_detail_id')
            ->join('products', 'products.id', '=', 'product_details.product_id')
            ->where('product_orders.payment_type','=','08');


        if (array_key_exists('unit', $params) && $params['unit']) {
            if ($params['unit'] != 'all') {
                $orders->where('ppdb_users.unit_id','=',$params['unit']);
            }
        }

        if (array_key_exists('payment_status', $params) && $params['payment_status']) {
            if ($params['payment_status'] != 'all') {
                $orders->where('product_orders.status','=',$params['payment_status']);
            }
        }


        if (array_key_exists('pickup_status', $params) && $params['pickup_status']) {
            if ($params['pickup_status'] != 'all') {
                $orders->where('product_orders.pickup_status','=',$params['pickup_status']);
            }
        }

        if (array_key_exists('date_range', $params) && $params['date_range']) {
            $dateStart = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
            $dateEnd = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
            $orders->where('product_orders.created_at', '>=', $dateStart)->where('product_orders.created_at', '<=', $dateEnd);
        }

        $orders = $orders->groupBy(['ppdb_users.unit_id','products.name','product_details.size','product_orders.status','product_orders.pickup_status','ppdb_users.name'])->get();

        return $orders;
    }

}
