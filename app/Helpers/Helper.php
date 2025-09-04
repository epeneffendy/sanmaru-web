<?php
namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\ProductOrder;
use Auth;
use App\Models\User;
use App\Models\Unit;
use App;
use App\Models\PPDBUser;
use App\Models\Student;

class Helper
{
    public static function isProduction()
    {
        return env('APP_ENV') === 'production';
    }

    public static function isStaging()
    {
        return env('APP_ENV') === 'staging';
    }

    public static function phoneWithLeadingZero($phone_number)
    {
        return preg_replace("/^62/", "0", $phone_number);
    }

    public static function tanggalJam($date)
    {
        setlocale(LC_TIME, 'id_ID');
        $date = Carbon::parse($date);
        $date->setLocale('id');
        return $date->isoFormat('D MMMM Y HH:mm');
    }

    public static function tanggal($date)
    {

        setlocale(LC_TIME, 'id_ID');
        $date = Carbon::parse($date);
        $date->setLocale('id');
        return $date->isoFormat('D MMMM Y');
    }

    public static function jam($date)
    {
        setlocale(LC_TIME, 'id_ID');
        $date = Carbon::parse($date);
        $date->setLocale('id');
        return $date->isoFormat('HH:mm');
    }

    public static function hariTanggalJam($date)
    {
        setLocale(LC_TIME, 'id_ID');
        $date = Carbon::parse($date);
        $date->setLocale('id');
        return $date->isoFormat('LLLL');
    }

    public static function invoiceNo($ppdb, $isPpdb = true)
    {
        $lastInvoice = ProductOrder::orderBy('id', 'desc')->first();

        if (!$isPpdb && $ppdb['nis']) {
            $invoiceNo = $ppdb['class']['unit']['unit_code'] . preg_replace('/\D/', '', $ppdb['nis']);
        } else {
            $invoiceNo = $ppdb['register_number'];
        }

        if ($lastInvoice) {
            $invoiceNo .= (sprintf("%07d", substr($lastInvoice->invoice_no, -7) + 1));
        } else {
            $invoiceNo .= '0000001';
        }

        return $invoiceNo;
    }

    public static function canPublishArticle()
    {
        return Auth::user()->type != 'author';
    }

    public static function isAuthorEditorRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['author', 'editor', 'admin', 'super_admin', 'ksp']);
    }
    public static function isShopRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['shop', 'admin', 'super_admin', 'ksp']);
    }
    public static function isPpdbRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['admin_ppdb', 'admin', 'super_admin', 'ksp']);
    }
    public static function isAdminRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['admin', 'super_admin', 'ksp']);
    }
    public static function isSuperAdminRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['super_admin']);
    }
    public static function isKspRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['ksp']);
    }
    public static function isEmployeeRole(User $user = null)
    {
        $user = $user ?: Auth::user();
        return in_array($user->type, ['admin', 'super_admin', 'pegawai']);
    }

    public static function hari($day)
    {
        $hari = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jum\'at',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];

        return array_key_exists($day, $hari) ? $hari[$day] : '';
    }

    public static function webUnits($webUnit)
    {
        $units = Unit::select('id', 'name', 'city')->get()->map(function ($unit) {
            $unit->webunit_slug = UnitHelper::webUnitSlug($unit);
            $unit->webunit_level = UnitHelper::level($unit->name);
            $unit->webunit_city_alias = UnitHelper::cityAlias($unit->city);
            return $unit;
        });

        $units = $units->where('webunit_slug', $webUnit);

        return $units;
    }

    public static function stringToNumber($input, $type='double', $round=false)
    {
        $result = 0;
        if(strpos($input,',')){
            $input = str_replace(',','',$input);
        }
        if(is_numeric($input)){
            settype($input,$type);
            $result = $input;
        }

        if($round){
            $result = round($result);
        }

        return $result;
    }

    public static function isVaBcaEnable()
    {
        // Deploy change
        return env("PAYMENT_BCA_ENABLE", false);
    }

    public static function isApiVaBcaEnable()
    {
        return env("PAYMENT_BCA_API_ENABLE", false);
    }

    public static function tanggalCicilan($date)
    {
        setlocale(LC_TIME, 'id_ID');
        $date = Carbon::parse($date);
        $date->setLocale('id');
        return $date->isoFormat('YYYY-MM-DD');
    }
}
