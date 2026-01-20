<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthStudentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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
            return redirect('welcome');
        }

        return view('student-dashboard.auth.student-login');
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
        $data = User::where(['username' => $request->username, 'type' => [User::STUDENT], 'status' => 'active'])
        ->with('student', 'ppdb')->first();

        $is_verfif_login = false;
        if(empty($data)){
            $data = User::where(['user_account' => $request->username, 'type' => [User::STUDENT], 'status' => 'active'])
                ->with('student', 'ppdb')->first();
            if(!empty($data)){
                if (Auth::guard('siswa')->attempt(['user_account' => $request->username, 'password' => $request->password, 'type' => [User::STUDENT], 'status' => 'active'])) {
                    $is_verfif_login = true;
                }
            }
        }else{
            if (Auth::guard('siswa')->attempt(['username' => $request->username, 'password' => $request->password, 'type' => [User::STUDENT], 'status' => 'active'])) {
                $is_verfif_login = true;
            }
        }

        if (empty($data->student->nis) && empty($data->student->register_number)) {
            $request->session()->flash('status', __('messages.failed.login_register_nis'));

            return back()->withInput();
        }


        // if (Auth::guard('siswa')->attempt(['username' => $request->username, 'password' => $request->password, 'type' => [User::STUDENT], 'status' => 'active'])) {
        if($is_verfif_login){
            // Authentication passed...
//            $user = Auth::guard('siswa')->user();
            $user = User::where('username', $data['username'])
                ->where('status', 'active')
                ->with('student','student.class','student.class.unit','ppdb', 'ppdb.unit', 'ppdb.period')
                ->where('type', 'siswa')
                ->first();

            $user->last_login_date = date('Y-m-d H:i:s');
            $user->save();

            session([
                'register-ppdb' => 'true',
                'user' => $user->toArray(),
            ]);

            return redirect()->route('welcome');
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

        return redirect()->route('login');
    }
}
