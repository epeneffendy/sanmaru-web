<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * @group User Actions
 *
 * APIs for user's actions
 */
class RegistrationController extends Controller
{
    /**
     * [POST] Register User
     *
     * Update user's password
     *
     * @urlParam type required type of user: guru, siswa, ppdb, vendor
     *
     * @bodyParam name string user's name. Example: Budi
     * @bodyParam email string user's name. Example: budi@anduk.com
     * @bodyParam mobile_phone integer user's mobile phone number. Example: 082132232323
     * @bodyParam password string new user's password. Example: newpassword
     * @bodyParam password_confirmation new password confirmation. Example: newpassword
     *
     *
     * @response {
     *   "data": {
     *       "email": "budi@anduk.com",
     *       "username": "budi",
     *       "type": "siswa",
     *       "mobile_phone": "6282132232323",
     *       "register_token": "$2y$10$R3e1GvblaIP4GmHAdQRXm.Awn90Gx9SDKRXQx/dI9qkUWdM0uK1BG",
     *       "status": "active",
     *       "updated_at": "2020-03-19 18:01:08",
     *       "created_at": "2020-03-19 18:01:08",
     *       "id": 5
     *   }
     *}
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *       "name": [
     *           "Nama harus diisi"
     *       ],
     *       "email": [
     *           "Email harus diisi"
     *       ],
     *       "mobile_phone": [
     *           "Nomor telepon harus diisi"
     *       ],
     *       "password": [
     *           "Password baru harus diisi"
     *       ]
     *   }
     *}
     *
     */
    public function register(
        RegistrationRequest $request,
        $type,
        UserService $userService,
        EmailService $emailService
    ) {
        $params = $request->validated();
        $user = $userService->register($type, $params, $emailService);
        $return = array(
            'data'    => $user->toArray(),
        );

        return response()->json($return, 200);
    }
}
