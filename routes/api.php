<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$routeService = App::make('App\Services\RouteService');
$helper = App::make('App\Helpers\Helper');

Route::group(['domain' => $routeService->getPpdbSubdomain()], function() use ($routeService) {
    Route::post('/register/{type}', 'RegistrationController@register');
});


Route::group(['domain' => $routeService->getBackendSubdomain()], function() {
    Route::post('/login', 'LoginController@auth');
    Route::get('/events', 'EventController@index');
    Route::get('/events/{id}', 'EventController@show');
    Route::post('/forget-password', 'LoginController@forgetPassword');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/attendance', 'AttendanceController@index');
        Route::post('/user/update-password', 'UserController@updatePassword');
        Route::get('/user/home-profile', 'UserController@getHomeProfile');
        Route::get('/courses', 'CourseController@index');
        Route::get('/assignments', 'CourseAssignmentController@index');
        Route::get('/uts', 'CourseUtsController@index');
        Route::get('/uas', 'CourseUasController@index');
        Route::get('/schedule/{day}/courses', 'CourseScheduleController@index');
        Route::get('/bill/categories', 'BillCategoryController@index');
        Route::get('/bill/{category_id}', 'UserBillController@index');
        Route::get('/bill/detail/{bill_user_id}', 'UserBillController@show');
        Route::get('/products/categories', 'ProductCategoryController@index');
        Route::get('/products/categories/{slug}', 'ProductCategoryController@show');
        Route::get('/products/types', 'ProductTypeController@index');
        Route::get('/products/types/{slug}', 'ProductTypeController@show');
        Route::get('/products/{categorySlug?}', 'ProductController@index');
        Route::get('/products/detail/{slug}', 'ProductController@show');
        Route::post('/cart/add', 'CartController@store');
        Route::post('/cart/remove', 'CartController@remove');
        Route::post('/cart/voucher', 'CartController@applyVoucher');
        Route::post('/cart/update', 'CartController@update');
        Route::get('/cart', 'CartController@index');

        Route::get('/blogs/categories/{slug}', 'BlogCategoryController@show');
        Route::get('/blogs/categories', 'BlogCategoryController@index');
        Route::get('/blogs/{slug}', 'BlogController@show');
        Route::get('/blogs', 'BlogController@index');

        Route::get('/faqs/{slug}', 'FaqController@show');
        Route::get('/faqs', 'FaqController@index');

        Route::get('/class-schedules', 'ClassScheduleController@index');

        Route::post('/orders/{order_id}/upload-payment', 'ProductOrderController@uploadPayment');
        Route::post('/orders/{order_id}/cancel', 'ProductOrderController@cancel');
        Route::get('/orders/{order_id}', 'ProductOrderController@show');
        Route::post('/orders', 'ProductOrderController@store');
        Route::get('/orders', 'ProductOrderController@index');
        // Route::get('/user', function (Request $request) {
        //     return $request->user();
        // });
        Route::get('/user/profile', 'UserController@getprofile');
        Route::get('/images/{file?}','ApiImageController@show')->where('file', '.*');
        Route::get('/file/{file?}', 'ApiFileController@show')->where('file', '.*');
    });
});

Route::group(['domain' => $routeService->getPaymentsSubdomain(), 'middleware' => ['cors']], function() use ($routeService, $helper) {
    $prefix = 'payment/';
    if ($routeService->isProduction()) {
        $prefix = '';
    }

    if ($helper->isVaBcaEnable()) {
        Route::post($prefix. 'v1.0/transfer-va/inquiry', 'Payment\OpenApi\v1\PaymentBCAController@inquiryList' )->name('payment.api.inquiry');
    }
});
