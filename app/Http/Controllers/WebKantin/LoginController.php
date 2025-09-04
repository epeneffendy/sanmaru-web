<?php

namespace App\Http\Controllers\WebKantin;

use App\Http\Requests\AuthStudentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:siswa')->only(['logout']);
    }


    public function landing()
    {
        if (Auth::guard('siswa')->user()) {
            return redirect(route('kantin.index'));
        } else {
            return view('webkantin.login');
        }
    }

    /**
     * Method for student login
     *
     * @param \App\Http\Requests\AuthStudentRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * */
    public function login(AuthStudentRequest $request)
    {
        if (Auth::guard('siswa')->attempt(['username' => $request->username, 'password' => $request->password, 'type' => [User::STUDENT], 'status' => 'active'])) {
            // Authentication passed...
            $user = Auth::guard('siswa')->user();
            $user->last_login_date = date('Y-m-d H:i:s');
            $user->save();

            return redirect(route('kantin.index'));
        } else {
            $request->session()->flash('status', __('messages.failed.login_student'));

            return back()->withInput();
        }
    }

    /**
     * Method for student logout
     *
     * @param \Illuminate\Http\Request $request
     *
     * */
    public function logout(Request $request)
    {
        if ($user = Auth::guard('siswa')->user()) {
            $user->last_logout_date = date('Y-m-d H:i:s');
            $user->save();
        }

        Auth::guard('siswa')->logout();

        return redirect(route('kantin.index'));
    }
}
