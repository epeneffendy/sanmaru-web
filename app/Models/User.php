<?php

namespace App\Models;

use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, FindSimilarUsernames;

    const TEACHER = 'guru';
    const STUDENT = 'siswa';
    const ADMIN = 'admin';
    const VENDOR = 'vendor';
    const PPDB = 'ppdb';
    const AUTHOR = 'author';
    const EDITOR = 'editor';
    const SHOP = 'shop';
    const SUPER_ADMIN = 'super_admin';
    const KSP = 'ksp';

    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     * 'name', 'email', 'username', 'password', 'type_enum', 'is_active', 'is_deleted', 'register_token', 'payment_status'
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_phone',
        'username',
        'password',
        'type',
        'status',
        'register_token',
        'user_type',
        'payment_status',
        'is_active',
        'role_units',
        'failed_login_counts',
        'last_login_date',
        'last_logout_date',
        'image_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'role_units' => 'array',
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRoleGuru($query)
    {
        return $query->where('type', 'guru');
    }

    public function scopeRoleSiswa($query)
    {
        return $query->where('type', 'siswa');
    }

    public function setUserTypeAttribute($value)
    {
        $this->type = $value;
    }

    public function setIsActiveAttribute($value)
    {
        $value = $value ? 'active' : 'inactive';
        $this->status = $value;
    }

    public function getIsActiveAttribute()
    {
        return $this->status == 'active';
    }

    public function getUserTypeAttribute()
    {
        return $this->type;
    }

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'user_id');
    }

    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'user_id');
    }

    public function parents()
    {
        return $this->hasMany('App\Models\Parents', 'children_id');
    }

    public function getNameAttribute()
    {
        $name = $this->username;

        if ($this->isPPDB()) {
            $name = $this->ppdb->name;
        }
        if ($this->isStudent()) {
            $name = $this->student->name;
        }
        if ($this->isTeacher()) {
            $name = $this->teacher->name;
        }

        return $name;
    }

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance', 'user_id');
    }

    public function ppdb()
    {
        return $this->hasOne('\App\Models\PPDBUser', 'user_id');
    }

    public function vendor()
    {
        return $this->hasOne('\App\Models\Vendor', 'user_id');
    }

    public function getImageAttribute()
    {
        $imagePath = isset($this->image_path) ? $this->image_path : '/img/profileimg.png';
        return url('images/' . $imagePath);
    }

    public function getRegistrationNumberAttribute()
    {
        if ($this->isPPDB()) {
            return $this->ppdb->register_number;
        }
        if ($this->isStudent()) {
            return $this->student->nis;
        }
        return null;
    }

    public function getUnitNameAttribute()
    {
        if ($this->isPPDB()) {
            return $this->ppdb->unit ? $this->ppdb->unit->name : null;
        }
        if ($this->isStudent()) {
            if ($this->student->class) {
                if ($this->student->class->unit) {
                    return $this->student->class->unit->name;
                }
            }
        }
        return null;
    }

    public function getSeragamVirtualAccountNumberAttribute()
    {
        if ($this->isPPDB()) {
            return \App\Helpers\PriceHelper::virtualAccountNumber($this->ppdb, true);
        }
        if ($this->isStudent()) {
            return \App\Helpers\PriceHelper::virtualAccountNumber($this->student, true);
        }
        return null;
    }

    public function isPPDB()
    {
        return $this->type == self::PPDB && $this->ppdb;
    }

    public function isStudent()
    {
        return $this->type == self::STUDENT && $this->student;
    }

    public function isTeacher()
    {
        return $this->type == self::TEACHER && $this->teacher;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            ActivityLog::createModel($product);
        });

        static::updated(function ($product) {
            ActivityLog::updateModel($product);
        });

        static::deleted(function ($product) {
            ActivityLog::deleteModel($product);
        });
    }
}
