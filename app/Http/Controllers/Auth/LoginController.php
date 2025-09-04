<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/administrator/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('administrator/login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        if($request->remember=="on"){
            $remember = true;
        }else{
            $remember = false;
        }
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'type' => ['admin', 'super_admin', 'admin_ppdb', 'shop', 'author', 'editor', 'ksp', 'pegawai'], 'status' => 'active'], $remember)) {
            // Authentication passed...
            $user = Auth::user();
            $user->last_login_date = date('Y-m-d H:i:s');
            $user->save();

            return redirect()->route('admin.dashboard.index');
        } else {
            $request->session()->flash('status', __('messages.failed.login'));
            return back()->withInput();
        }
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if ($user = Auth::user()) {
            $user->last_logout_date = date('Y-m-d H:i:s');
            $user->save();
        }

        Auth::logout();
        return redirect()->route('admin.login');
    }
}
