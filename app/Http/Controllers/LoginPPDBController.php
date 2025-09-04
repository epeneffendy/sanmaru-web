<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailForgotPasswordRequest;
use App\Http\Requests\RequestNewPasswordRequest;
use App\Http\Requests\NewPasswordWebRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Mail\EmailVerification;
use App\Services\UserService;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use App\Models\PPDBUser;
use App\Models\User;

class LoginPPDBController extends Controller
{
    private $page = "login-ppdb";

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        if ($request->session()->get('user') && $request->session()->get('register-ppdb')) {
            return redirect()->route('ppdb.welcome');
        }

        return view('ppdb-online/login');
    }

    public function accountSelect(Request $request)
    {
        $users = User::where('email', $request->email)->where('type', User::PPDB)->get();
        if ($users->count() > 0) {
            return view('ppdb-online.account-select', ['users' => $users]);
        } else {
            return redirect()
                ->route('ppdb.login')
                ->withErrors(['error' => 'email tidak ditemukan'])
                ->withInput();
        }
    }

    public function submit(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('ppdb.login')
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = array(
                'username' => $input['username'],
                'password' => $input['password'],
            );

            $user = User::where('username', $data['username'])
                ->where('status', 'active')
                ->with('ppdb', 'ppdb.unit', 'ppdb.period')
                ->where('type', 'ppdb')
                ->first();

            if (!empty($user)) {
                if (Hash::check($data['password'], $user['password'])) {
                    if ($user->ppdb->status == PPDBUser::STATUS_INCOMPLETE) {
                        return redirect()
                            ->route('ppdb.login')
                            ->withErrors(['error' => 'silahkan validasi akun anda dengan klik link / tautan yang dikirimkan ke alamat email anda.
                                                      <br>  Belum mendapat email verifikasi?
                                                    <input class="btn btn-sm btn-warning send-confirmation" id="confirmation" value="Kirim Ulang" data-id="'. $user->ppdb->id .'"/>'])
                            ->withInput();
                    }

                    $user->last_login_date = date('Y-m-d H:i:s');
                    $user->failed_login_counts = 0;
                    $user->save();

                    session([
                        'register-ppdb' => 'true',
                        'user' => $user->toArray(),
                    ]);
                    if ($user->ppdb->unreadNotifications) {
                        session(['unread_notification' => 'You have ' . $user->ppdb->unreadNotifications->count() . ' unread notification(s)']);
                    }

                    return redirect()->route('ppdb.welcome');
                } else {
                    $user->failed_login_counts++;
                    $user->save();
                }
            }

            return redirect()
                ->route('ppdb.login')
                ->withErrors(['error' => 'invalid email or password'])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('register-ppdb');
        $request->session()->forget('user-ppdb');

        if ($request->session()->get('user')) {
            $user = User::find($request->session()->get('user')['id']);
            $user->last_logout_date = date('Y-m-d H:i:s');
            $user->failed_login_counts = 0;
            $user->save();
        }

        return redirect()->route('ppdb.login');
    }

    public function sendEmailConfirmation($UserId, EmailService $emailService)
    {
        $ppdbUser = PPDBUser::where('id', $UserId)->firstOrFail();
        $user = $ppdbUser->user;

        $template = (new EmailVerification($user, $ppdbUser));
        if (isset($emailService) && $emailService){
            $emailService->sendMail($template, $user->email);
        }
    }

    public function sendEmailForgotPassword(
        EmailForgotPasswordRequest $request,
        UserService $userService
    ) {
        $params = $request->validated();
        $user = $userService->generateToken($params['username']);
        $template = (new ForgetPassword($user));
        (new EmailService())->sendMail($template, $user->email);
        return view('ppdb-online.send-to-email', ['user' => $user]);
    }

    public function requestPassword(RequestNewPasswordRequest $request)
    {
        $params = $request->validated();
        return view('ppdb-online.replace-password', $params);
    }

    public function newPassword(NewPasswordWebRequest $request, UserService $userService)
    {
        $params = $request->validated();
        $userService->newPasswordByToken($params);
        return redirect()->route('ppdb.login')->with('message', 'Password berhasil diubah!');
    }
}
