<?php

namespace App\Services;

use App\Mail\RegistrantConfirmed;
use TaylorNetwork\UsernameGenerator\Generator;
use Illuminate\Support\Facades\Hash;
use App\Transformer\UserTransformer;
use Illuminate\Support\Facades\Log;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\DB;
use App\Mail\EmailVerification;
use Illuminate\Support\Str;
use App\Models\PPDBUser;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Period;
use App\Models\StudentAdditionalData;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class UserService
{
    public function generateToken($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $user->remember_token = md5(Str::random(10) . uniqid(time(), true));
        $user->save();
        return $user;
    }

    public function newPasswordByToken($params)
    {
        $user = $this->findOrFailByAttribute('remember_token', $params['remember_token']);
        $user->password = Hash::make($params['password']);
        $user->remember_token = null;
        $user->save();

        return $user;
    }

    public function updatePassword($user, $oldPassword, $password)
    {
        $hasher = app('hash');
        throw_unless($hasher->check($oldPassword, $user->password), new UserException('Password is incorrect'));

        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * Reset password (ONLY FOR STUDENT IMPORT)
     * For https://aimsis.atlassian.net/browse/AIMSIS-10542
     *
     * @param User $user
     * @param String $newPassword
     * @return void
     */
     public function resetPassword($user, $newPassword)
     {
         try {
             $user->password = Hash::make($newPassword);
             $user->save();
             return $user;
         } catch (Exception $e){
             Log::error($e->getMessage());
             $exception = new UserException('User not found');
             throw $exception;
         }
     }

    public function register($type, $params, $emailService = null, $forceRegister = false)
    {
        try {
            $message = $errorCode = '';
            $ppdb = false;
            if (isset($params['nis'])) {
                $ppdb = true;
            }

            $isRegister = true;
            if ($ppdb) {
                $student = DB::table('students')
                    ->select('students.*')
                    ->join('classes', 'classes.id', '=', 'students.class_id')
                    ->where(['nis' => $params['nis'], 'classes.unit_id' => $params['unit_id']])
                    ->first();
                if (isset($student)) {
                    $isRegister = false;
                    $message = 'Data sudah tersedia';
                }
            } else {
                $isRegister = false;
                $ppdb = true;
                $message = 'Data sudah tersedia';
                if (isset($params['register_number']) && $params['register_number']) {
                    $student = DB::table('students')
                        ->select('students.*')
                        ->join('classes', 'classes.id', '=', 'students.class_id')
                        ->where(['register_number' => $params['register_number'], 'classes.unit_id' => $params['unit_id']])
                        ->first();
                    if (!isset($student)) {
                        $ppdb = false;
                        $isRegister = true;
                        $student = PPDBUser::where('register_number', $params['register_number'])->first();
                        if (!isset($student)) {
                            $isRegister = false;
                            $message = 'Tidak ditemukan dimenu pendaftar';
                        }
                    }
                }
            }

            if ($isRegister || $forceRegister) {
                DB::beginTransaction();
                if (!$ppdb && $type == User::STUDENT) {
                    if ($student) {
                        $student->update([
                            'status' => PPDBUser::STATUS_ACCEPTED,
                        ]);
                        $user = $student->user;

                        $this->generateUserTypeData($type, $user, $params, $emailService);
                        $user->refresh();

                        $user->type = User::STUDENT;
                        $user->save();
                    };
                } else {
                    $data = $this->generateUserData($type, $params);
                    $user = new User($data);
                    $user->save();

                    $this->generateUserTypeData($type, $user, $params, $emailService);
                    $user->refresh();

                    if(env('PAYMENT_REGISTRATION_FORM') == true){
                        $ppdbUser = PPDBUser::where('user_id', $user->id)->firstOrFail();
                        $bank_account = \App\Helpers\PriceHelper::paymentInfo($ppdbUser->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'];
                        $va_account = \App\Helpers\PriceHelper::virtualAccountNumber($ppdbUser, false, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL);

                        $ppdbUser->total_payment_form = \App\Helpers\PriceHelper::registration($ppdbUser, false);
                        $ppdbUser->expired_at = Carbon::parse($this->expiredRemining())->format('Y-m-d H:i:s');
                        $ppdbUser->payment_option = $bank_account;
                        $ppdbUser->virtual_account_number = $va_account;
                        $ppdbUser->save();
                    }
                }

                if ($isRegister){
                    $ppdbUser = PPDBUser::where('register_number', $params['register_number'])->first();

                    if ($ppdbUser) {
                        $template = (new RegistrantConfirmed($user, $ppdbUser));
                        (new EmailService())->sendMail($template, $user->email);
                    }
                }
                DB::commit();
                return $user;
            } else {
                DB::rollBack();
                $exception = new UserException('User registration is failed');
                $exception->additionalInfo = $message;
                throw $exception;
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $exception = new UserException('User registration is failed');
            if (isset($e->errorInfo)) {
                $errorInfo = $e->errorInfo;
                if (isset($errorInfo[1]) && $errorInfo[1] == 1062) {
                    $exception->additionalInfo = 'Email atau mobile phone sudah ada.';
                }
            }

            if (isset($e->additionalInfo)) {
                $exception->additionalInfo = $message;
            }
            throw $exception;
        }
    }

    private function generateUserData($type, $params)
    {
        return array(
            'email' => $params['email'],
            'username' => $this->username($params),
            'type' => $type,
            'mobile_phone' => app('phoneNormalizerService')->normalize($params['mobile_phone']),
            'password' => $this->password($params),
            'register_token' => Hash::make($params['email'] . date('Y-m-d H:i:s')),
            'status' => 'active',
        );
    }

    public function findOrFailByAttribute($attribute, $value)
    {
        return User::where($attribute, $value)->firstOrFail();
    }

    private function username($params)
    {
        $generator = new Generator(['separator' => '.']);
        if (isset($params['username'])) {
            $username = $_username = $params['username'];
        } elseif (isset($params['unit_id']) && isset($params['date_of_birth'])) {
            $unit = Unit::findOrFail($params['unit_id']);
            $dob = Carbon::parse($params['date_of_birth'])->format('dmY');
            $username = $_username = strtok(strtolower($params['name']), " ") . '.' . $unit->unit_code . '.' . $dob;
        } else {
            $username = $_username = $generator->generate($params['name']);
        }
        // $username = $_username = isset($params['username']) ? $params['username'] : $generator->generate($params['name']);
        while (User::where('username', $username)->first()) {
            $username = $_username . '.' . rand(1, 99);
        }

        return $username;
    }

    // https://aimsis.atlassian.net/browse/AIMSIS-10542
    // Alangkah bagusnya bila password default diganti menjadi tgl lahir siswa
    private function password($params)
    {
        $password = isset($params['password']) ? $params['password'] : 'sanmar12345';
        return Hash::make($password);
    }

    private function generateUserTypeData($type, $user, $params, $emailService)
    {
        switch ($type) {
            case User::STUDENT:
                $this->generateStudent($user, $params);
                break;
            case User::TEACHER:
                $this->generateTeacher($user, $params);
                break;
            case User::PPDB:
                $this->generatePPDBUser($user, $params['name'], $params['unit_id'], $emailService, @$params['origin_school'], $params);
                break;
            case User::VENDOR:
                $this->generateVendor($user, $params);
                break;
            case User::ADMIN:
                // admin currently do not need any additional data
                break;
        }
    }

    private function generatePPDBUser($user, $name, $unitId, EmailService $emailService = null, $originSchool = null, $params)
    {
        $period = Period::findOrFail($params['periode']);
        $data = array(
            'user_id' => $user->id,
            'name' => $name,
            'school_year' => $period->school_year,
            'address' => '-',
            'periode' => $params['periode'],
            'status' => 'incomplete',
            'unit_id' => $unitId,
            'nik_siswa' => $params['nik_siswa'],
            'nik_ortu' => $params['nik_ortu']
        );
        if ($originSchool) {
            $data['origin_school'] = $originSchool;
        }

        if (isset($params['class_option'])) {
            $data['class_option'] = $params['class_option'];
        }

        $ppdbUser = PPDBUser::create($data);

        // $update = [];
        // if (@$params['potensi_kecerdasan_image']) {
        //     if ($path = (new UploadService())->upload($params['potensi_kecerdasan_image'], 'potensi_kecerdasan')) {
        //         $update['potensi_kecerdasan_image'] = $path;
        //     }
        // }

        // if (@$params['bakat_istimewa_image']) {
        //     if ($path = (new UploadService())->upload($params['bakat_istimewa_image'], 'bakat_istimewa')) {
        //         $update['bakat_istimewa_image'] = $path;
        //     }
        // }

        // if (@$params['bakat_istimewa_image']) {
        //     if ($path = (new UploadService())->upload($params['bakat_istimewa_image'], 'bakat_istimewa')) {
        //         $update['kesiapan_psikis_image'] = $path;
        //     }
        // }

        // if (count($update)) {
        //     $ppdbUser->update($update);
        // }

        $this->generateRegisterNumber($ppdbUser);

        $template = (new EmailVerification($user, $ppdbUser));
        if (isset($emailService) && $emailService)
            $emailService->sendMail($template, $user->email);
    }

    private function generateTeacher($user, $params)
    {
        $data = array(
            'user_id' => $user->id,
            'nik' => $params['nik'],
            'name' => $params['name'],
            'email' => $params['email'],
            'mobile_phone' => $params['mobile_phone'],
            'address' => $params['address']
        );
        Teacher::create($data);
    }

    private function generateStudent($user, $params)
    {
        $data = array(
            'nis' => isset($params['nis']) ? $params['nis'] : NULL,
            'user_id' => $user->id,
            'name' => $params['name'],
            'email' => $params['email'],
            'mobile_phone' => $params['mobile_phone'],
            'address' => $params['address'],
            'class_id' => $params['class_id'],
            'school_year' => $params['school_year'],
            'register_number' => isset($params['register_number']) ? $params['register_number'] : NULL
        );
        $student = Student::create($data);

        $dataAdditional = [
            'student_id' => $student->id,
            'gender' => $params['gender'],
            'place_of_birth' => $params['place_of_birth'],
            'date_of_birth' => isset($params['date_of_birth']) ? Carbon::parse($params['date_of_birth'])->format('Y-m-d') : '',
            'city' => $params['city'],
            'region' => $params['region'],
            'country' => $params['country'],
            'religion' => $params['religion'],
        ];

        StudentAdditionalData::create($dataAdditional);
    }

    private function generateVendor($user, $params)
    {
        $data = array(
            'user_id' => $user->id,
            'name' => $params['name'],
            'address' => $params['address'],
            'city' => $params['city'],
            'mobile_phone' => $params['mobile_phone'],
            'pic' => $params['pic'],
            'nota_number' => $params['nota_number'],
            'nota_date' => $params['nota_date']
        );
        Vendor::create($data);
    }

    public function getProfile($user)
    {
        $student = Student::with('payment', 'class', 'class.unit')->where('user_id', $user->id)->first();
        $ppdb = PPDBUser::where('user_id', $user->id)->first();

        $user->ppdb = $ppdb;
        $user->student = $student;
        return (new UserTransformer)->transform($user);
    }

    public function generateRegisterNumber(PPDBUser $ppdb = null, $unit_id = null, $periode = null, $show = false)
    {
        //$year = date('y');
        // $year = 23;
        //hardcode for next year
        // $year++;
        // $year++;
        $period = Period::where('id', $periode ?: $ppdb->periode)->first();
        $year = substr(('' . $period->school_year), 2);

        $unitId = sprintf("%02d", $unit_id ?: $ppdb->unit_id);
        $urutan = PPDBUser::selectRaw('RIGHT(register_number, 3) as urutan')
            ->where('register_number', 'like', $year . '%')
            ->where('unit_id', $unit_id ?: $ppdb->unit_id)
            ->orderByRaw('RIGHT(register_number, 3) DESC')
            ->withTrashed();

        if ($period && $period->start_register_number) {
            $urutan = $urutan->whereRaw('RIGHT(register_number, 3) >= ' . $period->start_register_number);
        }

        $urutan = $urutan->first();

        if (!$urutan) {
            $urutan = $period && $period->start_register_number ? ($period->start_register_number - 1) : 0;
        } else {
            $urutan = (int)$urutan->urutan;
        }

        $urutan++;
        $count = sprintf("%03d", $urutan);

        $registerNumber = "{$year}{$unitId}{$count}";

        if ($show) {
            return $registerNumber;
        }

        return $ppdb->update(['register_number' => $registerNumber]);
    }

    public function expiredRemining(){

        $currentDateTime = Carbon::now();
        $expired_at = Carbon::now()->addDay();

        return $expired_at;
    }
}
