<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\EmailService;
use Illuminate\Http\Request;
use App\Mail\ForgetPassword;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\User;

/**
 * @group Login User
 *
 * APIs to login to the api service
 */
class LoginController extends Controller
{
    /**
     * [POST] Login to get token
     *
     * Logging in with username and password to get user's token to be used in authenticated API
     *
     * @response {
     *   "token": "3Uh3taUYBYkBf47FCzX6dIoS126LGtKjOnQyECjvhXKqhPFD82l42eZPDU3J",
     *   "user": {
     *       "id": 3,
     *       "username": "student1",
     *       "email": "student1@a.b.c",
     *       "mobile_phone": 6282122329293,
     *       "type": "siswa",
     *       "status": "active",
     *       "register_token": null,
     *       "deleted_at": null,
     *       "created_at": "2020-02-21 09:46:39",
     *       "updated_at": "2020-02-29 21:28:22"
     *   }
     *}
     *
     * @response 401 {
     *    "message": {
     *        "password": [
     *            "password wajib diisi."
     *        ]
     *    }
     *}
     *
     * @response 401 {
     *    "message": "Email/Username/NIS/Register Number is Not Found"
     *}
     *
     * @response 401 {
     *    "message": "Wrong Password"
     *}
     */
    public static function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'username/email/nis/register number harus diisi' 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 401);
        }

        $data = array(
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        );

        $user = User::where('username', $data['username'])
            ->orWhere('email', $data['username'])
            ->first();

        if (!$user) {
            $user = Student::where('nis', $data['username'])->orWhere('register_number', $data['username'])->with('user')->first();
            if ($user && $user->user) {
                $user = $user->user;
            }
        }

        if (empty($user)) {
            return response()->json(['message' => "Email/Username is Not Found"], 401);
        } else if (!Hash::check($data['password'], $user['password'])) {
            return response()->json(['message' => "Wrong Password"], 401);
        }

        $token = Str::random(60);
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
        $return = array(
            'token' => $token,
            'user'    => $user->toArray()
        );
        return response()->json($return);
    }

    /**
     * [POST] sending email to set new password
     *
     * Feature to help reset password by filling email to API
     *
     * @bodyParam email/username string user's email or username
     *
     * @response {
     *   "status": "success",
     *   "message": "silahkan cek email yang terdaftar dan klik tautan untuk membuat password baru"
     *}
     *
     * @response 401 {
     *    "message": {
     *        "username": [
     *            "username wajib diisi."
     *        ]
     *    }
     *}
     *
     * @response 401 {
     *    "message": "Email/Username is Not Found"
     *}
     *
     */
    public static function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 401);
        }

        $username = $request->input('username');

        $user = User::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (empty($user)) {
            return response()->json(['message' => "Email/Username is Not Found"], 401);
        }

        $user->remember_token = md5(Str::random(10) . uniqid(time(), true));
        $user->save();

        $template = (new ForgetPassword($user));
        (new EmailService())->sendMail($template, $user->email);

        $return = array(
            'status' => 'success',
            'message' => 'silahkan cek email yang terdaftar dan klik tautan untuk membuat password baru'
        );
        return response()->json($return);


    }
}
