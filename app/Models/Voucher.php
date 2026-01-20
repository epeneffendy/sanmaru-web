<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Helpers\PriceHelper;
use Auth;

class Voucher extends Model
{
    use Notifiable, SoftDeletes;

    const TYPE_FREE = 'free_product';
    const TYPE_DISC_FIXED = 'discount_fixed';
    const TYPE_DISC_PERCENT = 'discount_percent';

    const USAGE_CUMULATIVE = 'cumulative';
    const USAGE_PER_USER = 'per_user';

    const STATUS_CLAIMED = 'claimed';
    const STATUS_AVAILABLE = 'available';

    public function types()
    {
        return [
            self::TYPE_FREE,
            self::TYPE_DISC_FIXED,
            self::TYPE_DISC_PERCENT
        ];
    }

    protected $casts = [
        'rule' => 'array',
        'unit_id' => 'array',
        'user_id' => 'array'
    ];

    protected $fillable = [
        'code',
        'type',
        'rule',
        'unit_id',
        'usage_limit',
        'active',
        'user_id',
        'note',
        'usage_type',
        'year',
        'target_siswa',
        'period_id',
        'unit_student',
    ];

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id', 'id');
    }

    public function getAll($limit = null, $page = null, $paginate = false, Request $request)
    {
        $gets = $this->orderBy('created_at', 'desc');

        //super dirty
        if (Auth::user()->role_units && count(Auth::user()->role_units)) {
            $gets = $gets->where(function($q) {
                $q->where('unit_id', NULL);
                foreach (Auth::user()->role_units as $unit) {
                    $q->orWhere('unit_id', 'like', '%"'. $unit .'"%');
                }
            });

            $gets = $gets->where(function($q) {
                $q->where('user_id', NULL);
                foreach (PPDBUser::whereIn('unit_id', Auth::user()->role_units)->select('user_id')->get() as $user) {
                    $q->orWhere('user_id', 'like', '%"'. $user->user_id .'"%');
                }
            });
        }

        if ($request->input('name')) {
            $gets = $gets->where('code', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('unit')) {
            $gets = $gets->where(function($query) use ($request) {
                return $query->where(function ($q) {
                    return $q->where('unit_id', NULL)->where('user_id', NULL);
                })->orWhere('unit_id', 'like', '%"'. $request->input('unit') .'"%');
            });
        }
        if ($request->input('type')) {
            $gets = $gets->where('type', $request->input('type'));
        }

        if ($paginate) {
            $gets = $gets->paginate($limit);
        } else {
            if ($limit) {
                $gets = $gets->take($limit);
            }

            if ($page) {
                $gets = $gets->offset( ($page - 1) * $limit );
            }

            $gets = $gets->get();
        }

        $unit_id = [];
        $product_id = [];
        $user_id = [];

        foreach ($gets as $get) {
            if ($get->type === self::TYPE_FREE && $products = json_decode($get->rule, TRUE)) {
                foreach ($products as $product) {
                    $product_id[$product] = $product;
                }
            }
            if ($get->unit_id) {
                foreach ($get->unit_id as $unit) {
                    $unit_id[$unit] = $unit;
                }
            }
            if ($get->user_id) {
                foreach ($get->user_id as $user) {
                    $user_id[$user] = $user;
                }
            }
        }

        $units = Unit::whereIn('id', $unit_id)->get()->keyBy('id');
        $products = Product::whereIn('id', $product_id)->get()->keyBy('id');
        $users = User::whereIn('id', $user_id)->get()->keyBy('id');

        foreach ($gets as $key=>$get) {
            $gets[$key]->units = null;
            $gets[$key]->products = null;
            $gets[$key]->users = null;

            if ($get->type === self::TYPE_FREE && $product = json_decode($get->rule, TRUE)) {
                $gets[$key]->products = collect();
                foreach ($product as $product) {
                    $gets[$key]->products->push($products[$product]);
                }
            }
            if ($get->unit_id) {
                $gets[$key]->units = collect();
                foreach ($get->unit_id as $unit) {
                    $gets[$key]->units->push($units[$unit]);
                }
            }
            if ($get->user_id) {
                $gets[$key]->users = collect();
                foreach ($get->user_id as $user) {
                    $gets[$key]->users->push($users[$user]);
                }
            }

        }

        return $gets;
    }

    public function getTargetAttribute()
    {
        if ($this->unit_id) {
            return 'Khusus Unit';
        }

        if ($this->user_id) {
            return 'Khusus Siswa';
        }

        return 'Semua';
    }

    public function getUsageRemainingAttribute()
    {
        if ($this->usage_limit === -1) {
            return 'Tidak ada batas';
        }

        return $this->usage_limit;
    }

    public function getProductAttribute()
    {
        if ($this->type === 'free_product') {
            return json_decode($this->rule, TRUE);
        }

        return [];
    }

    public function getActiveLabelAttribute()
    {
        return '<label class="label '. ($this->active ? 'label-success' : 'label-danger') .'">'. ($this->active ? 'Aktif' : 'Nonaktif')  .'</label>';
    }

    public function getTypeValueAttribute()
    {
        if ($this->type === 'discount_fixed') {
            return '<label class="label label-primary">'. PriceHelper::rupiah($this->rule) .'</label>';
        }

        if ($this->type === 'discount_percent') {
            return '<label class="label label-primary">'. $this->rule .'%</label>';
        }

        if ($this->products) {
            $return = '';
            foreach ($this->products as $product) {
                $return .= '<label class="label label-primary">'. $product->name .'</label><br/>';
            }

            return $return;
        }

        return null;
    }

    public function getTargetValueAttribute()
    {

        if ($this->unit_id) {
            $units = Unit::whereIn('id', $this->unit_id)->get();
            $return = '';
            foreach ($units as $unit) {
                $return .= '<label class="label label-info">'. $unit->name .'</label><br/>';
            }

            return $return;
        }

        if ($this->user_id) {
            $users = User::whereIn('id', $this->user_id)->with(['ppdb', 'student'])->get();
            $return = '';
            foreach ($users as $user) {
                if (@$user->ppdb) {
                    $return .= '<label class="label label-info">'. @$user->ppdb->name .'</label><br/>';
                } elseif (@$user->student) {
                    $return .= '<label class="label label-info">'. @$user->student->name .'</label><br/>';
                } else {
                    $return .= '<label class="label label-info"> </label><br/>';
                }
            }

            return $return;
        }
        return null;
    }

    public static function eligible(array $user)
    {
        $vouchers = self::select('id', 'rule', 'unit_id', 'user_id', 'type', 'usage_limit', 'usage_type', 'note', 'code')->where('usage_limit', '<>', 0)->where('active', true)->with('usages', 'usages.orders')->get();
        $eligible = collect();

        if ($vouchers) {
            if ($coll = $vouchers->filter(function($voucher) use ($user) {

                //self cumulative
                if ($voucher->user_id && in_array($user['id'], $voucher->user_id) && $voucher->usage_type === self::USAGE_CUMULATIVE) {
                    return true;
                }

                //unit cumulative
                if ($user['type'] == User::PPDB && isset($user['ppdb'])) {
                    if ($voucher->unit_id && in_array($user['ppdb']['unit_id'], $voucher->unit_id) && $voucher->usage_type === self::USAGE_CUMULATIVE) {
                        return true;
                    }
                }

                if ($user['type'] == User::STUDENT && isset($user['student'])) {
                    if ($voucher->unit_id && in_array($user['student']['class']['unit_id'], $voucher->unit_id) && $voucher->usage_type === self::USAGE_CUMULATIVE) {
                        return true;
                    }
                }

                //all cumulative
                if (!$voucher->unit_id && !$voucher->user_id && $voucher->usage_type === self::USAGE_CUMULATIVE) {
                    return true;
                }

                //self per_user
                if ($voucher->user_id && in_array($user['id'], $voucher->user_id) && $voucher->usage_type === self::USAGE_PER_USER && ($voucher->usage_limit < 0 || $voucher->usage_limit > $voucher->usages->filter(function($usage) use ($user) {
                    return $usage->orders->filter(function($po) use ($user) {return $user['id'] === $po->user_id && $po->status !== ProductOrder::STATUS_CANCEL; })->first() ? true : false;
                })->count())) {
                    return true;
                }

                //unit per_user
                if($user['type'] == User::PPDB && isset($user['ppdb'])){
                    if ($voucher->unit_id && in_array($user['ppdb']['unit_id'], $voucher->unit_id) && $voucher->usage_type === self::USAGE_PER_USER && ($voucher->usage_limit < 0 || $voucher->usage_limit > $voucher->usages->filter(function($usage) use ($user) {
                        return $usage->orders->filter(function($po) use ($user) {return $user['id'] === $po->user_id && $po->status !== ProductOrder::STATUS_CANCEL; })->first() ? true : false;
                    })->count())) {
                        return true;
                    }
                }

                //all per_user
                if (!$voucher->unit_id && !$voucher->user_id && $voucher->usage_type === self::USAGE_PER_USER && ($voucher->usage_limit < 0 || $voucher->usage_limit > $voucher->usages->filter(function($usage) use ($user) {
                    return $usage->orders->filter(function($po) use ($user) {return $user['id'] === $po->user_id && $po->status !== ProductOrder::STATUS_CANCEL; })->first() ? true : false;
                })->count())) {
                    return true;
                }

                return false;
            })->all()) {
                $eligible = $eligible->merge($coll);
            }
        }

        return $eligible;
    }

    public function reduceUsage()
    {
        if ($this->usage_type === self::USAGE_PER_USER) {
            return;
        }

        if ($this->usage_limit === -1) {
            return;
        }

        $this->usage_limit = $this->usage_limit - 1;
        if ($this->usage_limit < 0) {
            $this->usage_limit;
        }

        return $this->save();
    }

}
