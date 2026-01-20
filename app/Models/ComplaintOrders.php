<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasActivityLogs;
use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Model;

class ComplaintOrders extends Model
{
    use HasActivityLogs;
    use Auditable;
    use ImageHandler;

    const STATUS_WAITING = 'waiting';
    const STATUS_PROCESS = 'process';
    const STATUS_DONE = 'done';
    const STATUS_CANCEL = 'cancel';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PICKUP = 'pickup';

    protected $table = 'complaint_orders';

    protected $appendsAttachment = ['image_attachment'];
    protected $appendsAttachmentAddition = ['image_addition'];
    protected $appendsAttachmentExtra = ['image_extra'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    public function productOrderDetail()
    {
        return $this->belongsTo(ProductOrderDetail::class, 'product_order_id','product_order_id')
            ->where('product_id', $this->product_id);
    }

    public function complaintCategory()
    {
        return $this->belongsTo(ComplaintCategory::class);
    }

    public function getImageAttachmentAttribute()
    {
        return (empty($this->attachment)) ?  '' : $this->getImageUrl($this->attachment);
    }

    public function getImageAdditionAttribute()
    {
        return (empty($this->attachment_addition)) ?  '' : $this->getImageUrl($this->attachment_addition);
    }

    public function getImageExtraAttribute()
    {
        return (empty($this->attachment_extra)) ? '': $this->getImageUrl($this->attachment_extra);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusAttribute()
    {
        if($this->status == self::STATUS_WAITING){
            return  'Menunggu';
        }

        if($this->status == self::STATUS_PROCESS){
            return  'Diproses';
        }

        if($this->status == self::STATUS_PICKUP){
            return  'Menunggu Pengambilan';
        }

        if($this->status == self::STATUS_CANCEL){
            return  'Batal';
        }

        if($this->status == self::STATUS_REJECTED){
            return  'Ditolak';
        }

        if($this->status == self::STATUS_DONE){
            return  'Selesai';
        }
    }

}
