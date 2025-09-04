<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Config;
use DB;

class Student extends Model
{
    use Notifiable, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    /**
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nis',
        'user_id',
        'name',
        'email',
        'mobile_phone',
        'address',
        'class_id',
        'school_year',
        'class',
        'register_number',
        'image_path',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__ . '\User', 'id', 'user_id');
    }

    public function class()
    {
        return $this->hasOne(__NAMESPACE__. '\Classes', 'id', 'class_id');
    }

    public function payment()
    {
        return $this->hasOne(__NAMESPACE__ . '\PaymentAgreement', 'id', 'payment_agreement_id');
    }

    public function additionalData()
    {
        return $this->hasOne(StudentAdditionalData::class, 'student_id', 'id');
    }

    public function parents()
    {
        return $this->hasMany(Parents::class, 'children_id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

//        static::saved(function($student) {
//            if (Config::get('database.connections.mysql_erp')) {
//                $student->refresh();
//                $studentUser = $student->user;
//
//                if ($studentUser) {
//                    $user = DB::connection('mysql_erp')->table('users')
//                        ->where('email', $studentUser->email)
//                        ->orWhere('username', Str::slug($studentUser->name, '.'))
//                        ->first();
//
//                    if (!$user) {
//                        $userId = DB::connection('mysql_erp')->table('users')->insertGetId([
//                            'email' => $studentUser->email,
//                            'username' => $studentUser->username,
//                            'sanmaru_user_id' => $studentUser->user_id,
//                            'type' => 'siswa'
//                        ]);
//                    } else {
//                        $userId = $user->id;
//                    }
//                }
//
//                $array = [
//                    'nis' => $student->nis,
//                    'name' => $student->name,
//                    'email' => $student->email,
//                    'mobile_phone' => $student->mobile_phone,
//                    'address' => $student->address,
//                    'class' => @$student->class->name,
//                    'unit' => @$student->class->unit->name,
//                    'school_year' => $student->school_year,
//                    'image_path' => $student->image_path,
//                    'register_number' => $student->register_number,
//                    'user_id' => $userId
//                ];
//
//                DB::connection('mysql_erp')->table('students')->updateOrInsert([
//                    'id' => $student->id
//                ], $array);
//            }
//        });
//
//        static::deleting(function($student) {
//            if (Config::get('database.connections.mysql_erp')) {
//                DB::connection('mysql_erp')->table('students')->where('id', $student->id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
//            }
//        });
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $style = 'success';
                $text = 'Aktif';
                break;
            case self::STATUS_INACTIVE:
                $style = 'danger';
                $text = 'Tidak Aktif';
                break;
            default:
                $style = 'info';
                $text = 'Tidak Terdefinisi';
                break;
        }
        return "<label class='label label-$style'>$text</label>";
    }

    public static function getAvailableStatuses() : array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
        ];
    }
}
