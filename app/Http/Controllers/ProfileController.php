<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewPasswordRequest;
use App\Models\Parents;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();
        $student = $user->student()->with('additionalData', 'class', 'class.unit', 'parents')->firstOrFail();;
        $studentAdditionalData = $student->additionalData;
        $unit = optional($student->class)->unit;
        $dad = $student->parents->firstWhere('type', Parents::TYPE_FATHER);
        $data = array(
            'nav' => ['parent' => 'profile', 'child'=>'Profile'],
            'student' => [
                'name' => $student->name,
                'unit' => optional($unit)->name,
                'place_of_birth' => optional($studentAdditionalData)->place_of_birth,
                'date_of_birth' => optional($studentAdditionalData)->date_of_birth,
                'address' => $student->address,
                'gender' => optional($studentAdditionalData)->gender,
                'origin_school' => optional($studentAdditionalData)->asal_sekolah,
                'religion' => optional($studentAdditionalData)->religion,
                'photo' => null,
                'register_number' => $student->register_number,
                'mobile_phone' => $student->mobile_phone,
                'dad_mobile_phone' => optional($dad)->phone,
            ],
        );

        return view('student-dashboard.profile.student-profile', $data);
    }

    public function changePassword()
    {
        $data = array(
            'nav' => ['parent' => 'profile', 'child' => 'Ubah Password']
        );
        return view('student-dashboard.profile.change-password', $data);
    }

    public function changePasswordSubmit(NewPasswordRequest $request, UserService $userService)
    {
        $params = $request->validated();
        $user = Auth::guard('siswa')->user();

        try {
            $userService->updatePassword($user, $params['old_password'], $params['password']);
        } catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Password lama anda salah']);
        }

        return redirect()->route('profile')->with('message', 'Password berhasil diubah!');
    }
}
