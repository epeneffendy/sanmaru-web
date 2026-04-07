<?php

namespace App\Http\Controllers;

use App\Http\Requests\PPDBRegisterRequest;
use App\Models\Period;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PPDBVerifyRequest;
use App\Http\Controllers\Controller;
use App\Services\PPDBUserService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\AgeLimit;
use App\Models\User;
use App\Models\Unit;

class RegistrasiPPDBController extends Controller
{
    private $page = "registrasi-ppdb";

    public function index(Request $request)
    {
        if ($request->session()->has('register-ppdb')) {
            return redirect()->route('ppdb.welcome');
        }

        return view('ppdb-online.index', [
            'units' => Unit::get()
        ]);
    }

    public function register($unitName = null)
    {
        if (is_null($unitName)) {
            abort(404);
        }

        $unit = Unit::where('name', $unitName)->firstOrFail();
        $ageLimit = AgeLimit::active()->first();

        $periods = Period::where('unit_id',$unit->id)
            ->where('active',1)
            ->get();

        return view('ppdb-online.registration', [
            'unit' => $unit,
            'ageLimit' => $ageLimit,
            'periods'=>$periods
        ]);
    }

    public function profile($unitName)
    {
        $unit = Unit::where('name', $unitName)->firstOrFail();
        return view("ppdb-online.profile.profile", [
            'unit' => $unit
        ]);
    }

    public function insert(PPDBRegisterRequest $request, UserService $userService, EmailService $emailService)
    {
        $input = $request->validated();
        $user = $userService->register(User::PPDB, $input, $emailService, true);

        $data = [
            'page' => $this->page,
            'email' => $input['email'],
            'user' => $user
        ];

        return view('ppdb-online.success', $data);
    }

    public function verify(PPDBVerifyRequest $request, PPDBUserService $PPDBUserService)
    {
        $input = $request->validated();

        $user = $PPDBUserService->getDataFromRegisterToken($input['v']);

        if ($user->isEmailVerified) {
            return redirect()->route('ppdb.login')->with('verified', 'email sudah terverifikasi sebelumnya, silahkan login');
        }

        $verified = $PPDBUserService->verify($user->id);
        if ($verified) {
            return redirect()->route('ppdb.login')->with('verified', 'email telah terverifikasi, silahkan login');
        }

        return abort(404);
    }
}
