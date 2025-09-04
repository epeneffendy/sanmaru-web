<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;
use Illuminate\Support\Str;
use Auth;
use DB;
use Config;

class Unit extends Model
{
    use Notifiable, SoftDeletes, ImageHandler;

    /**
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'city',
        'unit_code',
        'address',
        'email',
        'phone',
        'image_path',
        'banner_path',
        'about',
        'keunggulan',
        'procedure',
        'helpdesk',
        'payment_option',
        'keunggulan_path',
        'header_info',
        'present_color',
        'telp',
        'fax',
    ];

    protected $casts = [
        'telp' => 'array',
        'fax' => 'array',
    ];

    public function testimonies()
    {
        return $this->hasMany('App\Models\Testimony', 'unit_id', 'id');
    }

    public function periods()
    {
        return $this->hasMany('App\Models\Period', 'unit_id', 'id');
    }

    public function activePeriods()
    {
        return $this->hasMany('App\Models\Period', 'unit_id', 'id')
            ->where('active', true)
            ->orderBy('start_date');
    }

    public function activeStages()
    {
        return $this->hasMany(Stage::class)
            ->where('active', true);
    }

    public function scopeByUserRole($query, $userId = false)
    {
        $user = Auth::user();

        if ($userId instanceof User) {
            $user = $userId;
        } elseif ($userId) {
            $user = User::where('id', $userId)->first();
        }
        if (isset($user)){
            if (!$user->role_units || ($user->role_units && !count($user->role_units))) {
                return $query;
            }
            return $query->whereIn('id', $user->role_units);
        }
    }

    public function ppdbUsers()
    {
        return $this->hasMany(PPDBUser::class, 'unit_id', 'id');
    }

    public function totalUsersPaymentNotYetSubmitted()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'complete' && $value->payment_form == '';
            })->count('id');
        }

        return 0;
    }

    public function totalUsersPaymentNotYetVerified()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'complete' && $value->payment_form != '';
            })->count('id');
        }

        return 0;
    }

    public function totalUsersEmailNotVerified()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'incomplete';
            })->count('id');
        }

        return 0;
    }

    public function totalUsersSubmittedRegistration()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'submitted' || $value->status === 'rejected' || $value->status === 'accepted';
            })->count('id');
        }

        return 0;
    }

    public function userOrders()
    {
        return $this->hasMany(PPDBUser::class, 'unit_id', 'id')->with('orders', 'orders.user');
    }

    public function totalUsersOrder()
    {
        $collections = $this->userOrders;

        if ($collections) {
            return $collections->filter(function ($value) {
                return count($value->orders);
            })->count('id');
        }

        return 0;
    }

    public function totalUsersNotOrder()
    {
        $collections = $this->userOrders;

        if ($collections) {
            return $collections->filter(function ($value) {
                return count($value->orders) <= 0;
            })->count('id');
        }

        return 0;
    }

    public function totalUsersOrderPaymentNotConfirmed()
    {
        $collections = $this->userOrders;

        if ($collections) {
            return $collections->filter(function ($value) {
                return $value->orders->filter(function ($order) {
                    return $order->payment_image == null && $order->status <> ProductOrder::STATUS_CONFIRMED;
                })->count('id');
            })->count('id');
        }

        return 0;
    }

    public function totalUsersOrderPaymentUploaded()
    {
        $collections = $this->userOrders;

        if ($collections) {
            return $collections->filter(function ($value) {
                if (count($value->orders->where('status', ProductOrder::STATUS_CONFIRMED))) {
                    return 0;
                }
                return $value->orders->filter(function ($order) {
                    return $order->payment_image <> null && $order->status <> ProductOrder::STATUS_CONFIRMED;
                })->count('id');
            })->count('id');
        }

        return 0;
    }

    public function totalUsersOrderPaymentConfirmed()
    {
        $collections = $this->userOrders;

        if ($collections) {
            return $collections->filter(function ($value) {
                return $value->orders->filter(function ($order) {
                    return $order->status == ProductOrder::STATUS_CONFIRMED;
                })->count('id');
            })->count('id');
        }

        return 0;
    }

    public function ongoingPeriods()
    {
        return $this->hasMany('App\Models\Period', 'unit_id', 'id')
            ->where('active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString());
    }

    public function scopeAgeLimitApplied($query)
    {
        return $query->where(function ($q) {
            // TODO::prepare for dynamic
            $q->where('name', 'like', 'SD%')
            ->orWhere('name', 'like', 'TK%')
            ->orWhere('name', 'like', 'KB%')
            ->orWhere('name', 'like', 'SMP%')
            ->orWhere('name', 'like', 'SMA%');
        });
    }

    public function getIsAgeLimitAppliedAttribute()
    {
        if (@in_array(explode('-', $this->name)[0], ['SD', 'KB', 'TK', 'SMP', 'SMA'])) {
            return true;
        }

        return false;
    }

    public function getImageAttribute()
    {
        return (empty($this->image_path)) ?  app('url')->to('/img/Sanmaru Logo.png') : $this->getImageUrl($this->image_path);
    }

    public function getBannerAttribute()
    {
        return (empty($this->banner_path)) ?  app('url')->to('/img/Sanmaru Logo.png') : $this->getImageUrl($this->banner_path);
    }

    public function getPhotoAttribute()
    {
        return (empty($this->testimonies->photo_path)) ?  app('url')->to('/img/Sanmaru Logo.png') : $this->getImageUrl($this->testimonies->photo_path);
    }

    public function getKeunggulanImagePathAttribute()
    {
        $unit = \App\Helpers\ImageHelper::unit_advantage($this->name);
        return (empty($this->keunggulan_path)) ?  $unit : $this->getImageUrl($this->keunggulan_path);
    }

    public function getIsDataCompleteAttribute()
    {
        return $this->name != '' &&
            $this->city != '' &&
            $this->unit_code != '' &&
            $this->address != '' &&
            $this->email != '' &&
            $this->phone != '' &&
            $this->about != '' &&
            $this->keunggulan != '';
    }

    public function syncTestimonies($details)
    {
        $ids = [];
        foreach ($details as $detail) {
            if ($detail['id']) {
                $testimony = Testimony::find($detail['id']);
                if (!isset($detail['photo_path'])){
                    $detail['photo_path'] = $testimony->photo_path;
                }

            } else {
                $testimony = Testimony::firstOrNew([
                    'unit_id' => $this->id,
                    'subject' => $detail['subject'],
                    'job' => $detail['job'],
                    'content' => $detail['content'],
                    'photo_path' => isset($detail['photo_path']) ? $detail['photo_path']:''
                ]);
            }
            if (!isset($detail['unit_id'])) {
                $detail['unit_id'] = $this->id;
            }
            $testimony->fill($detail);
            $testimony->save();
            $ids[] = $testimony->id;
        }

        Testimony::where('unit_id', $this->id)->whereNotIn('id', $ids)->delete();
    }

    public function getNameWithFileFormatAttribute()
    {
        return Str::upper(Str::slug($this->name, '-'));
    }

    public function costs()
    {
        return $this->hasMany(UnitCost::class);
    }

    public function syncCosts($params)
    {
        $this->costs
            ->whereNotIn('id', $params['unit_cost_ids'])
            ->each(function($cost) {
                $cost->delete();
            });

        foreach ($params['cost_titles'] as $key => $cost) {
            if ($params['cost_titles'][$key] != '') {
                $this->costs()->updateOrCreate(
                    [
                        'id' => $params['unit_cost_ids'][$key],
                        'unit_id' => $this->id
                    ],
                    [
                        'title' => $params['cost_titles'][$key],
                        'description' => $params['cost_descriptions'][$key]
                    ]
                );
            }
        }
    }

    public function getNameSantaMariaAttribute()
    {
        $centerName = "Santa Maria";

        $explodedFullName = explode("-", Str::upper(Str::slug($this->name)));

        if (count($explodedFullName) == 2)
            if ($explodedFullName[1] == 'PACET') {
                $centerName = "Santo Yusup";
            }
            if (in_array($explodedFullName[0], ['KB', 'TK', 'SD', 'SMP', 'SMA']) && $explodedFullName[1] == 'SIDOARJO') {
                $centerName = "Santa Maria II";
            }
        return $explodedFullName[0] . " " . $centerName . " " . Str::title($explodedFullName[1]);

        return $this->name;
    }

    public function getLevelOfEducationAttribute()
    {
        $names = explode('-', $this->name);
        if (@in_array($names[0], ['SD', 'KB', 'TK', 'SMP', 'SMA'])) {
            return $names[0];
        }

        return null;
    }

    public function getLetterHeaderNameAttribute()
    {
        $centerName = "Santa Maria";
        $vaFullname = explode("-", Str::upper(Str::slug($this->name)));
        if (count($vaFullname) == 2) {
            if ($vaFullname[1] == 'PACET') {
                $centerName = "Santo Yusup";
            }
            if ($vaFullname[1] == 'SURABAYA' && in_array($vaFullname[0], ['KB', 'TK'])) {
                return "KB - TK " . $centerName;
            }
            if ($vaFullname[1] == 'SIDOARJO' && in_array($vaFullname[0], ['KB', 'TK'])) {
                $centerName = "Santa Maria II";
                return "KB - TK " . $centerName;
            }
            if ($vaFullname[1] == 'SIDOARJO') {
                $centerName = "Santa Maria II";
            }
            return $vaFullname[0] . " " . $centerName;
        }

        return $this->name;
    }

    public function getLetterHeaderCityAttribute()
    {
        if (! $this->city) {
            return null;
        }
        $city = implode(" ", str_split(Str::upper($this->city)));
        if ($this->city == 'surabaya') {
            $city .= " 60265";
        }

        return $city;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function($unit) {
            if (Config::get('database.connections.mysql_erp')) {
                $name = str_replace('-', ' ', strtoupper($unit->name));
                $unit_erp = DB::connection('mysql_erp')->table('units')
                    ->where('name', 'like' , '%'.$name.'%')
                    ->first();

                if ($unit_erp) {
                    DB::connection('mysql_erp')->table('units')->where('id', $unit_erp->id)->update([
                        'sanmaru_unit_id' => $unit->id,
                        'sanmaru_unit_name' => $unit->name,
                    ]);
                }
            }
        });
    }
}
