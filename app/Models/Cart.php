<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'status', 'voucher'
    ];

    protected $dates = ['deleted_at'];

    public function details()
    {
        return $this->hasMany(CartDetail::class, 'cart_id' ,'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getGrandTotalAttribute()
    {
        $details = $this->details;
        $total = 0;

        $isPricePPDBApplied = false;
        if ($this->user->type == 'ppdb' && ProductDetail::isPricePPDBApplied()) {
            $isPricePPDBApplied = true;
        }

        foreach ($details as $detail) {
            if ($isPricePPDBApplied) {
                $total += ($detail->quantity * $detail->product_detail->price_ppdb);
            } else {
                $total += ($detail->quantity * $detail->product_detail->price_siswa);
            }
        }

        return $total;
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
                foreach ($this->details as $detail) {
                    if (in_array($detail->product_id, $rule)) {
                        if ($isPricePPDBApplied) {
                            $total += $detail->product_detail->price_ppdb;
                        } else {
                            $total += $detail->product_detail->price_siswa;
                        }

                        if (($key = array_search($detail->product_id, $rule)) !== false) {
                            unset($rule[$key]);
                        }
                    }
                }
                return $total;
            }

            if ($voucher['type'] === Voucher::TYPE_DISC_FIXED) {
                return intVal($voucher['rule']);
            }

            if ($voucher['type'] === Voucher::TYPE_DISC_PERCENT) {
                return round(($voucher['rule'] / 100) * $this->grand_total, 2);
            }
        }

        return 0;
    }

    public function getGrandTotalAfterDiscountAttribute()
    {
        $gt = $this->grand_total;
        $dc = $this->discount_total;

        return $gt - $dc < 0 ? 0 : $gt - $dc;
    }
}