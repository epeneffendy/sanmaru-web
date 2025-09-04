<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ImageHandler;
use ReflectionClass;
use Carbon\Carbon;

class ProductOrder extends Model
{
    use SoftDeletes, ImageHandler;

    const STATUS_NEW_ORDER = 'new_order';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PICKUP = 'pickup';
    const STATUS_DONE = 'done';
    const STATUS_CANCEL = 'cancel';

    const PICKUP_STATUS_NOT_PICKUP = 'not_pickup';
    const PICKUP_STATUS_PICKUP = 'pickup';
    const PICKUP_STATUS_SENT = 'sent';

    const PAYMENT_STATUS_NOT_CONFIRMED = 'payment_not_confirmed';
    const PAYMENT_STATUS_UPLOADED = 'payment_uploaded';
    const PAYMENT_STATUS_CONFIRMED = 'payment_confirmed';

    const MAX_EXPIRED_DAYS = 1;

    protected $fillable = [
        'user_id',
        'invoice_no',
        'transaction_no',
        'voucher',
        'payment_image',
        'status',
        'pickup_status',
        'pickup_date',
        'pickup_date_schedule',
        'alt_pickup_date_schedule',
        'pickup_start_time',
        'pickup_end_time',
        'pickup_location',
        'pickup_notes',
        'pickup_image',
        'payment_confirmed_date',
        'payment_confirmed_mail_sent',
        'payment_type',
        'payment_cancel_date',
        'payment_cancel_reason',
        'order_amount',
        'total_payment',
        'payment_inquiry_id',
        'virtual_account_number',
        'payment_option'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productOrderDetails()
    {
        return $this->hasMany(ProductOrderDetail::class);
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    public function refunds()
    {
        return $this->hasMany('App\Models\PaymentRefund', 'refund_id','id');
    }

    public function overpayment()
    {
        return $this->hasOne('App\Models\PaymentRefund', 'refund_id', 'id')->where('cause', 'overpayment');
    }

    public function uniformPayment()
    {
        return $this->hasOne('\App\Models\UniformPayment', 'product_order_id', 'id');
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case $this::STATUS_NEW_ORDER:
                return '<label class="label label-warning">Order Baru</label>';
                break;
            case $this::STATUS_CONFIRMED:
                return '<label class="label label-info">Terkonfirmasi</label>';
                break;
            case $this::STATUS_PICKUP:
                return '<label class="label label-primary">Pengambilan</label>';
                break;
            case $this::STATUS_DONE:
                return '<label class="label label-success">Selesai</label>';
                break;
            case $this::STATUS_CANCEL:
                return '<label class="label label-danger">Batal</label>';
                break;
            default:
                return '';
                break;
        }
    }

    public function getKantinStatusLabelAttribute()
    {
        if ($this->status == self::STATUS_CANCEL) {
            return '<div class="text-sm reguler order-status-danger">Cancel</div>';
        } elseif (empty($this->payment_image)) {
            return '<div class="text-sm reguler order-status-danger">Segera upload bukti bayar</div>';
        } elseif ($this->status != self::STATUS_CONFIRMED && !empty($this->payment_image)) {
            return '<div class="text-sm reguler order-status-warning">Menunggu konfirmasi</div>';
        } elseif (($this->status === $this::STATUS_CONFIRMED) && (!$this->isPickup()) && empty($this->pickup_date_schedule)) {
            return '<div class="text-sm reguler order-status-primary">Terkonfirmasi</div>';
        } elseif ($this->needPickup()) {
            return '<div class="text-sm reguler order-status-primary">Siap Diambil pada '.date_format(date_create($this->pickup_date_schedule),"l,j F Y").' </div>';
        } elseif ($this->isPickup()) {
            return '<div class="text-sm reguler order-status-primary">Selesai</div>';
        }

        return '';
    }

    public function getLabelKonfirmasiPembayaranAttribute()
    {
        if ($this->status !== $this::STATUS_CONFIRMED && $this->payment_image) {
            return '<label class="label" style="color: #007bff">Bukti Pembayaran Telah Terupload</label>';
        }

        if ($this->status === $this::STATUS_CONFIRMED) {
            return '<label class="label" style="color: #28a745">Konfirmasi Pembayaran Diterima</label>';
        }

        if ($this->status === $this::STATUS_CANCEL) {
            return '<label class="label" style="color: #dc3545">Pembatalan Order</label>';
        }

        return '<label class="label" style="color: #dc3545">Pembayaran Belum Terkonfirmasi</label>';
    }

    public function getIconKonfirmasiPembayaranAttribute()
    {
        if ($this->status !== $this::STATUS_CONFIRMED && $this->payment_image) {
            return '<span class="btn btn-circle btn-sm btn-primary"><icon class="icon-plus"><i class="fa fa-check" title="sudah upload namun belum terkonfirmasi admin"></i></icon></span>';
        }

        if ($this->status === $this::STATUS_CONFIRMED) {
            return '<span class="btn btn-circle btn-sm btn-success"><icon class="icon-plus"><i class="fa fa-check" title="sudah upload dan sudah dikonformasi admin"></i></icon></span>';
        }

        return '<span class="btn btn-circle btn-sm btn-danger"><icon class="icon-plus"><i class="fa fa-times" title="belum upload bukti pembayaran"></i></icon></span>';
    }

    public function getIconEmailKonfirmasiPembayaranAttribute()
    {
        if ($this->payment_confirmed_mail_sent) {
            return '<span class="btn btn-circle btn-sm btn-primary"><icon class="icon-plus"><i class="fa fa-check"></i></icon></span>';
        }

        return '<span class="btn btn-circle btn-sm btn-danger"><icon class="icon-plus"><i class="fa fa-times"></i></icon></span>';
    }

    public function getPaymentImageUrl()
    {
        if ($this->payment_image == null) {
            return null;
        }

        return $this->getImageUrl($this->payment_image);
        // return route('show_image', ['file' => $this->payment_image]);
    }

    public function getDiscountTotalAttribute()
    {
        $voucher = json_decode($this->voucher, TRUE);

        $isPricePPDBApplied = false;
        if ($this->user->type == 'ppdb' && ProductDetail::isPricePPDBApplied()) {
            $isPricePPDBApplied = true;
        }

        if ($voucher) {
            if ($voucher['type'] === Voucher::TYPE_FREE) {
                $total = 0;
                $rule = json_decode($voucher['rule'], TRUE);
                foreach ($this->productOrderDetails as $detail) {
                    if (in_array($detail->product_id, $rule)) {
                        if ($isPricePPDBApplied) {
                            $total += $detail->productDetail->price_ppdb;
                        } else {
                            $total += $detail->productDetail->price_siswa;
                        }

                        if (($key = array_search($detail->product_id, $rule)) !== false) {
                            unset($rule[$key]);
                        }
                    }
                }
                return $total;
            }

            if ($voucher['type'] === Voucher::TYPE_DISC_FIXED) {
                return $voucher['rule'];
            }

            if ($voucher['type'] === Voucher::TYPE_DISC_PERCENT) {
                return round(($voucher['rule'] / 100) * $this->productOrderDetails->sum('total_price'), 2);
            }
        }

        return 0;
    }

    public function getGrandTotalAttribute()
    {
        $gt = $this->productOrderDetails->sum('total_price');
        $dt = $this->discount_total;

        return $gt - $dt < 0 ? 0 : $gt - $dt;
    }

    public function getGrandTotalGrossAttribute()
    {
        return $this->productOrderDetails->sum('total_price');
    }

    public function getPpdbUserAttribute()
    {
        return PPDBUser::where(['user_id' => $this->user->id])->first();
    }

    public function listOrderStatus()
    {
        $constantWithLabel = [];
        $constants = (new ReflectionClass($this))->getConstants();

        foreach($constants as $key => $constant) {
            $is_status = (strtoupper(substr($key, 0, 6)) == "STATUS");
            if($constant != 'created_at' && $constant != 'updated_at' && $is_status)
                $constantWithLabel[] = [
                    'value' => $constant,
                    'name' => ucwords(str_replace("_", " ", $constant))
                ];
        }

        return $constantWithLabel;
    }

    public function listPickupStatus()
    {
        $constantWithLabel = [];
        $constants = (new ReflectionClass($this))->getConstants();

        foreach($constants as $key => $constant) {
            $is_status = (strtoupper(substr($key, 0, 6)) == "PICKUP");
            if($constant != 'created_at' && $constant != 'updated_at' && $is_status)
                $constantWithLabel[] = [
                    'value' => $constant,
                    'name' => ucwords(str_replace("_", " ", $constant))
                ];
        }

        return $constantWithLabel;
    }

    public function listPaymentStatus()
    {
        $constantWithLabel = [];
        $constants = (new ReflectionClass($this))->getConstants();

        foreach($constants as $key => $constant) {
            $is_status = (strtoupper(substr($key, 0, 7)) == 'PAYMENT');
            if ($constant != 'created_at' && $constant != 'updated_at' && $is_status)
                $constantWithLabel[] = [
                    'value' => $constant,
                    'name' => ucwords(str_replace("_", " ", $constant))
                ];
        }

        return $constantWithLabel;
    }

    public function setInvoiceNoAttribute($value)
    {
        if ($value) {
            return $this->attributes['invoice_no'] = $value;
        }

        $lastInvoice = self::orderBy('invoice_no', 'desc')->first();
        if ($lastInvoice) {
            $newInvoiceNo = str_pad($lastInvoice->invoice_no + 1, 6, 0, STR_PAD_LEFT);
        } else {
            $newInvoiceNo = Carbon::now()->format('dmY') . '000001';
        }

        return $this->attributes['invoice_no'] = $newInvoiceNo;
    }

    public function syncDetails($params)
    {
        $this->productOrderDetails()
            ->whereNotIn('product_detail_id', $params['product_order_detail_id'])
            ->get()
            ->each(function($productOrderDetail) {
                $productOrderDetail->delete();
            });

        foreach ($params['product_id'] as $key => $productId) {
            $this->productOrderDetails()->updateOrCreate(
                [
                    'product_id' => $productId,
                    'product_order_id' => $this->id,
                    'product_detail_id' => $params['product_detail_id'][$key]
                ],
                [
                    'quantity' => $params['qty'][$key],
                    'note' => isset($params['note'][$key]) ? $params['note'][$key] : null,
                    'total_price' => $params['price'][$key] * $params['qty'][$key]
                ]
            );
        }

        return $this->productOrderDetails;
    }

    public function getFirstImageThumbnail()
    {
        return $this->productOrderDetails ? $this->productOrderDetails->first()->product->image : app('url')->to('/img/default-seragam.jpg');
    }

    public function getFirstImageThumbnailKantin()
    {
        return $this->productOrderDetails ? $this->productOrderDetails->first()->product->image : app('url')->to('webkantin/images/menu-1.png');
    }

    protected static function boot() {
        parent::boot();

        static::created(function ($productOrder) {
            if ($voucher = json_decode($productOrder->voucher, TRUE)) {
                Voucher::where('id', $voucher['id'])->first()->reduceUsage();
            }
            $cart = Cart::where('user_id', $productOrder->user_id)->first();
            if (! is_null($cart)) {
                $cart->update(['voucher' => null]);
            }
        });
    }

    public function getPickupStatusLabelAttribute()
    {
        switch ($this->pickup_status) {
            case $this::PICKUP_STATUS_NOT_PICKUP:
                return '<label class="label label-warning">Belum diambil</label>';
                break;
            case $this::PICKUP_STATUS_PICKUP:
                return '<label class="label label-success">Diambil</label>';
                break;
            case $this::PICKUP_STATUS_SENT:
                return '<label class="label label-primary">Dikirim</label>';
                break;
        }
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->status !== $this::STATUS_CONFIRMED && $this->payment_image) {
            return $this::PAYMENT_STATUS_UPLOADED;
        }

        if ($this->status === $this::STATUS_CONFIRMED) {
            return $this::PAYMENT_STATUS_CONFIRMED;
        }

        return $this::PAYMENT_STATUS_NOT_CONFIRMED;
    }

    public function getKonfirmasiPembayaranAttribute()
    {
        switch ($this->payment_status) {
            case $this::PAYMENT_STATUS_NOT_CONFIRMED :
                return 'pembayaran belum terkonfirmasi';
            case $this::PAYMENT_STATUS_UPLOADED :
                return 'bukti pembayaran telah terupload';
            case $this::PAYMENT_STATUS_CONFIRMED :
                return 'konfirmasi pembayaran diterima';
            default :
                return 'pembayaran belum terkonfirmasi';
        }
    }

    public function getPickupAndScheduleStatusAttribute()
    {
        if ($this->pickup_status != self::PICKUP_STATUS_NOT_PICKUP) {
            return 'Sudah Diambil';
        } else if ($this->pickup_status == self::PICKUP_STATUS_NOT_PICKUP) {
            return 'Belum Diambil';
        } else if (!is_null($this->pickup_date_schedule)) {
            return 'Dijadwalkan';
        } else if (is_null($this->pickup_date_schedule) && $this->pickup_status == self::PICKUP_STATUS_NOT_PICKUP) {
            return 'Belum Dijadwalkan';
        } else {
            return 'Tidak Terdefinisi';
        }
    }

    public function scopePaymentUploaded($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CONFIRMED, self::STATUS_CANCEL])
            ->whereNotNull('payment_image');
    }

    public function scopePaymentConfirmed($query)
    {
        return $query->where('status', '=', $this::STATUS_CONFIRMED);
    }

    public function scopePaymentNotConfirmed($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CONFIRMED, self::STATUS_CANCEL])
            ->whereNull('payment_image');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', $this::STATUS_CANCEL);
    }

    public function scopeNotCanceled($query)
    {
        return $query->where('status', '<>', $this::STATUS_CANCEL);
    }

    public function scopePickup($query)
    {
        return $query->where('pickup_status', '<>', $this::PICKUP_STATUS_NOT_PICKUP)->whereNotNull('pickup_date');
    }

    public function isPickup()
    {
        return ($this->pickup_status <> $this::PICKUP_STATUS_NOT_PICKUP) && (!is_null($this->pickup_date));
    }

    public function needPickup()
    {
        return ($this->status === $this::STATUS_CONFIRMED) && (!$this->isPickup()) && $this->pickup_date_schedule;
    }

    public function getPickupImageUrl()
    {
        if ($this->pickup_image == null) {
            return null;
        }

        return $this->getImageUrl($this->pickup_image);
    }

    public function getExpiredAtAttribute()
    {
        $created_at = $this->created_at;
        return $created_at->addDays(self::MAX_EXPIRED_DAYS);
    }

    public function getExpiredDaysRemainingAttribute()
    {
        $time_exp = strtotime($this->expired_at->format('Y-m-d'));
        $time_now = strtotime(Carbon::now()->format('Y-m-d'));
        return ($time_exp - $time_now) / (60*60*24);
    }

    public function syncPayment($product_order_id)
    {
        $order_amount = $this->productOrderDetails->sum('total_price');
        $discount = $this->discount_total;

        $total_payment = $order_amount - $discount < 0 ? 0 : $order_amount - $discount;

        $productOrder = ProductOrder::where([
            'status'=> ProductOrder::STATUS_NEW_ORDER,
            'id'=>$product_order_id
        ])->firstOrFail();

        if ($productOrder->update([
            'order_amount' => $order_amount,
            'total_payment' => $total_payment
        ]));
    }
}
