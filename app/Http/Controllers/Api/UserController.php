<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewPasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * @group User Actions
 *
 * APIs for user's actions
 */
class UserController extends Controller
{
    /**
     * [POST] Update Password
     *
     * Update user's password
     *
     * @bodyParam old_password string user's password to be changed. Example: password
     * @bodyParam password string new user's password. Example: newpassword
     * @bodyParam password_confirmation new password confirmation. Example: newpassword
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "Password update is successful!"
     *        }
     *    ]
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function updatePassword(NewPasswordRequest $request, UserService $userService)
    {
        $params = $request->validated();
        $userService->updatePassword($request->user(), $params['old_password'], $params['password']);
        $return = array(
            'message'    => 'Password update is successful!',
        );

        return response()->json($return, 200);
    }

    /**
     * [GET] Retrieve Home Profile
     *
     * Retrieve user's profile for home page
     *
     * @urlParam billId id of bill wanna be shown
     * @response {
     *  "data": {
     *      "username": 'user1',
     *      "name": "User satu",
     *      "image": "path/to/image",
     *  }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function getHomeProfile(Request $request)
    {
        $return = array(
            'username' => $request->user()->username,
            'name' => $request->user()->name,
            'image' => $request->user()->image
        );

        return response()->json($return, 200);
    }

    /**
     * [GET] Retrieve Profile Complete
     *
     * Retrieve user's profile for profile page
     *
     * @response {
     *   "user": {
     *       "username": "dandy",
     *       "email": "dandyfirmansyah1998@gmail.com",
     *       "mobile_phone": 6282113843687,
     *       "type": "admin",
     *       "status": "active"
     *   },
     *   "student": {
     *       "nis": "9999",
     *       "name": "Dandy Firmansyah",
     *       "email": "dandyfirmansyah@email.com",
     *       "mobile_phone": "08123365511",
     *       "address": "Malang Sawojajar",
     *       "unit_name": "Unit 1",
     *       "class_name": "class 1",
     *       "payment_agreement_name": "Payment Agreements 1",
     *       "school_year": 2020,
     *       "gender": "male",
     *       "origin_school": "Sekolah Dulu",
     *       "ttl": "Malang, 1 Agustus 2000",
     *       "religion": "Kristen",
     *       "number_of_siblings": "2"
     *   },
     *   "parents": [
     *       {
     *           "name": "Bapak Bro",
     *           "place_of_birth": "Lumajang",
     *           "date_of_birth": "2020-02-05",
     *           "address": "Malang",
     *           "city": "Malang",
     *           "region": "Jawa Timur",
     *           "country": "Indonesia",
     *           "religion": "Islam",
     *           "phone": 871233222,
     *           "job": "Pengusaha",
     *           "card_identity": null,
     *           "type": "father"
     *       },
     *       {
     *           "name": "Ibu Bro",
     *           "place_of_birth": "Malang",
     *           "date_of_birth": "1978-01-01",
     *           "address": "Malang",
     *           "city": "Malang",
     *           "region": "Jawa Timur",
     *           "country": "Indonesia",
     *           "religion": "Islam",
     *           "phone": 6287912332,
     *           "job": "IRT",
     *           "card_identity": null,
     *           "type": "mother"
     *       }
     *   ]
     * }
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */

    public function getProfile(Request $request, UserService $userservice)
    {
        $data = $userservice->getProfile($request->user());
        return response()->json($data);
    }
}
