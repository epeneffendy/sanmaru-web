<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class PaymentRefund extends Model
{
	use ImageHandler;

    const STATUS_NEW_REFUND = 'new_refund';
    const STATUS_CONFIRMED = 'confirmed';

    const CAUSE_REPAYMENT = 'repayment';
    const CAUSE_OVERPAYMENT = 'overpayment';

    const TYPE_UNIFORM = 'uniform';
    const TYPE_DEVELOPMENT = 'development';
    const TYPE_REGISTRASI = 'registrasi';
    const TYPE_OTHER = 'other';

    protected $table = 'payment_refunds';

    protected $fillable = [
    	'user_id',
    	'refund_id',
    	'refund_type',
        'cause',
        'status',
        'nominal_price',
    	'nominal_refund',
    	'refund_image',
    	'note',
    	'updated_by_id'
    ];

    public function getRefundModel()
    {
        switch($this->refund_type){
            case 'uniform':
                return 'ProductOrder';
            default:
                return 'Finance';
        }
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function refund()
    {
        return $this->belongsTo(__NAMESPACE__."\\".$this->getRefundModel(), 'refund_id', 'id');
    }

    public function scopeRepaymentUniform($query)
    {
        return $query->where('refund_type', 'uniform')->where('cause', $this::CAUSE_REPAYMENT);
    }

    public function scopeRepaymentDevelopment($query)
    {
        return $query->where('refund_type', 'development')->where('cause', $this::CAUSE_REPAYMENT);
    }

    public function scopeOverpayment($query)
    {
        return $query->where('cause', $this::CAUSE_OVERPAYMENT);
    }

    public function getRefundNameAttribute()
    {
    	switch ($this->refund_type) {
            case 'uniform':
                return 'Seragam';
            case 'registrasi':
                return 'Registrasi / Pendaftaran';
            case 'development':
                return 'Pembinaan / Uang Gedung';
            case 'tuition':
                return 'SPP';
            case 'other':
                return 'Lain-lain';
            default:
                return null;
    	}
    }

    public function getRefundImageUrl() {
    	return (empty($this->refund_image)) ?  null : $this->getImageUrl($this->refund_image);
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case $this::STATUS_NEW_REFUND:
                return '<label class="label label-warning">Pengembalian Baru</label>';
                break;
            case $this::STATUS_CONFIRMED:
                return '<label class="label label-info">Terkonfirmasi</label>';
                break;
            default:
                return '';
                break;
        }
    }

    public function getIconKonfirmasiPembayaranAttribute()
    {
        if ($this->status !== $this::STATUS_CONFIRMED && $this->refund_image) {
            return '<span class="btn btn-circle btn-sm btn-primary"><icon class="icon-plus"><i class="fa fa-check" title="sudah upload namun belum terkonfirmasi admin"></i></icon></span>';
        }

        if ($this->status === $this::STATUS_CONFIRMED) {
            return '<span class="btn btn-circle btn-sm btn-success"><icon class="icon-plus"><i class="fa fa-check" title="sudah upload dan sudah dikonfirmasi admin"></i></icon></span>';
        }

        return '<span class="btn btn-circle btn-sm btn-danger"><icon class="icon-plus"><i class="fa fa-times" title="belum upload bukti pembayaran"></i></icon></span>';
    }

    public function isConfirmed() {
        return $this->status === $this::STATUS_CONFIRMED;
    }

    public function listCause()
    {
        $constantWithLabel = [];
        $constants = (new ReflectionClass($this))->getConstants();
    
        foreach($constants as $key => $constant) {
            $is_status = (strtoupper(substr($key, 0, 5)) == "CAUSE");
            if($constant != 'created_at' && $constant != 'updated_at' && $is_status)
                $constantWithLabel[] = [
                    'value' => $constant,
                    'name' => ucwords(str_replace("_", " ", $constant))
                ];
        }

        return $constantWithLabel;
    }
    public function productOrders()
    {
        return $this->belongsTo('App\Models\ProductOrder', 'refund_id', 'id');
    }
}
