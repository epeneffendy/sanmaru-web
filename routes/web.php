<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

$routeService = App::make('App\Services\RouteService');
$helper = App::make('App\Helpers\Helper');

Route::group(['domain' => $routeService->getPpdbSubdomain()], function () use ($routeService, $helper) {
    $prefix = 'ppdb-online';
    if ($routeService->isProduction()) {
        $prefix = '';
    }

    //PPDB ONLINE
    Route::group(['middleware' => 'web'], function () use ($prefix) {
        Route::get("/$prefix", 'RegistrasiPPDBController@index')->name('ppdb.index');
        Route::get("/$prefix/landing-page", 'RegistrasiPPDBController@indexLandingPage')->name('ppdb.index-landing-page');
        Route::post("/$prefix/submit", 'RegistrasiPPDBController@insert')->name('ppdb.insert');
        Route::any("/$prefix/success", 'RegistrasiPPDBController@success')->name('ppdb.success');
        Route::get("/$prefix/register/{unitName?}", 'RegistrasiPPDBController@register')->name('ppdb.register');
        Route::get("/$prefix/profile/{unitName}", 'RegistrasiPPDBController@profile')->name('ppdb.profile');
        Route::get("/$prefix/login", 'LoginPPDBController@login')->name('ppdb.login');
        Route::post("/$prefix/login/account-select", 'LoginPPDBController@accountSelect')->name('ppdb.login.account-select');
        Route::post("/$prefix/login/submit", 'LoginPPDBController@submit')->name('ppdb.login.submit');
        Route::get("/$prefix/logout", 'LoginPPDBController@logout')->name('ppdb.logout');
        Route::get("/$prefix/verify", 'RegistrasiPPDBController@verify')->name('ppdb.verify');
        Route::post("/$prefix/email-sended", 'LoginPPDBController@sendEmailForgotPassword')->name('ppdb.email-sended');
        Route::get('/forgot-password/verification', 'LoginPPDBController@requestPassword')->name('ppdb.request-password');
        Route::post('/new-password', 'LoginPPDBController@newPassword')->name('ppdb.new-password');
        Route::post("/$prefix/send-confirmation/{UserId}", 'LoginPPDBController@sendEmailConfirmation')->name('ppdb.email-confirmation');
    });

    Route::group(['middleware' => ['web', 'register-ppdb']], function () use ($prefix) {
        Route::get('/images/{file?}', 'ImageController@getImage')->where('file', '.*');
        Route::get("/$prefix/welcome", 'PPDBController@welcome')->name('ppdb.welcome');
        Route::post("/$prefix/welcome/submit", 'PPDBController@welcomeStudentSubmit')->name('ppdb.welcome.submit');
        Route::get("/$prefix/informasi-ppdb", 'PPDBController@informasiPpdb')->name('ppdb.informasi-ppdb');
        Route::get("/$prefix/biaya-pengembangan", 'PPDBController@biayaPengembanganPpdb')->name('ppdb.biaya-pengembangan');
        Route::get("/$prefix/biaya-pengembangan/cicilan", 'PPDBController@biayaPengembanganCicilanPpdb')->name('ppdb.biaya-pengembangan.cicilan');
        Route::get("/$prefix/biaya-pengembangan/lunas", 'PPDBController@biayaPengembanganLunasPpdb')->name('ppdb.biaya-pengembangan.lunas');
        Route::post("/$prefix/biaya-pengembangan/cicilan", 'PPDBController@postBiayaPengembanganCicilanPpdb')->name('ppdb.biaya-pengembangan.post-cicilan');
        Route::get("/$prefix/biaya-pengembangan/lainnya", 'PPDBController@biayaPengembanganLainnyaPpdb')->name('ppdb.biaya-pengembangan.lainnya');
        Route::get("/$prefix/biaya-pengembangan/download", 'PPDBController@downloadDevelopmentStatement')->name('ppdb.download-biaya-pengembangan');
        Route::get("/$prefix/faq-ppdb", 'PPDBController@faqPpdb')->name('ppdb.faq-ppdb');
        Route::get("/$prefix/notifikasi-ppdb", 'PPDBController@notifikasiPpdb')->name('ppdb.notifikasi-ppdb');
        Route::get("/$prefix/data-siswa-ppdb", 'PPDBController@dataSiswaPpdb')->name('ppdb.data-siswa-ppdb');
        Route::get("/$prefix/finance-bills", 'PPDBController@financeBills')->name('ppdb.finance-bills');
        Route::get("/$prefix/profile-siswa-ppdb", 'PPDBController@profileSiswa')->name('ppdb.profile-siswa');
        Route::get("/$prefix/change-password", 'PPDBController@newPassword')->name('ppdb.change-password.form');
        Route::post("/$prefix/change-password", 'PPDBController@updatePassword')->name('ppdb.change-password.update');
        Route::get("/$prefix/form-student", 'PPDBController@formStudent')->name('ppdb.form-student');
        Route::post("/$prefix/form-student/submit", 'PPDBController@formStudentSubmit')->name('ppdb.form-student.submit');
        Route::get("/$prefix/form-parent", 'PPDBController@formParent')->name('ppdb.form-parent');
        Route::post("/$prefix/form-parent/submit", 'PPDBController@formParentSubmit')->name('ppdb.form-parent.submit');
        Route::post("/$prefix/upload-payment-form", 'PPDBController@uploadPaymentForm')->name('ppdb.upload-payment-form');
        Route::post("/$prefix/upload-birth-certificate", 'PPDBController@uploadBirthCertificate')->name('ppdb.upload-birth-certificate');
        Route::post("/$prefix/upload-development-fee", 'PPDBController@uploadDevelopmentFee')->name('ppdb.upload-development-fee');
        Route::post("/$prefix/upload-photo", 'PPDBController@uploadPhoto')->name('ppdb.upload-photo');
        Route::post("/$prefix/upload-family-card", 'PPDBController@uploadFamilyCard')->name('ppdb.upload-family-card');
        Route::post("/$prefix/upload-parent-identity-card", 'PPDBController@uploadParentIdentityCard')->name('ppdb.upload-parent-identity-card');
        Route::post("/$prefix/upload-baptismal-certificate", 'PPDBController@uploadBaptismalCertificate')->name('ppdb.upload-baptismal-certificate');
        Route::post("/$prefix/upload-rekomendasi-bk", 'PPDBController@uploadRekomendasiBk')->name('ppdb.upload-rekomendasi-bk');
        Route::post("/$prefix/upload-marriage-certificate", 'PPDBController@uploadMarriageCertificate')->name('ppdb.upload-marriage-certificate');
        Route::post("/$prefix/upload-kartu-golongan-darah", 'PPDBController@uploadKartuGolonganDarah')->name('ppdb.upload-kartu-golongan-darah');
        Route::post("/$prefix/upload-kms", 'PPDBController@uploadKms')->name('ppdb.upload-kms');
        Route::post("/$prefix/upload-award-photo", 'PPDBController@uploadAwardPhoto')->name('ppdb.upload-award-photo');
        Route::post("/$prefix/upload-report-card", 'PPDBController@uploadReportCard')->name('ppdb.upload-report-card');
        Route::post("/$prefix/delete-report-card", 'PPDBController@deleteReportCard')->name('ppdb.delete-report-card');
        Route::get("/$prefix/download-statement-letter", 'PPDBController@downloadStatementLetter')->name('ppdb.download-statement-letter');
        Route::get("/$prefix/download-angket-peminatan", 'PPDBController@downloadAngketPeminatan')->name('ppdb.download-angket-peminatan');
        Route::post("/$prefix/upload-statement-letter", 'PPDBController@uploadStatementLetter')->name('ppdb.upload-statement-letter');
        Route::post("/$prefix/upload-angket-peminatan", 'PPDBController@uploadAngketPeminatan')->name('ppdb.upload-angket-peminatan');
        Route::get("/$prefix/download-proof-registration", 'PPDBController@downloadProofRegistration')->name('ppdb.download-proof-registration');
        Route::get("/$prefix/download-development-statement-letter", 'PPDBController@getDevelopmentStatementLetterFile')->name('ppdb.download-development-statement-letter');
        Route::post("/$prefix/reset-development-fee", 'PPDBController@postResetDevelopmentFee')->name('ppdb.reset-development-fee.post');
        Route::get("/$prefix/product", 'PPDBController@product')->name('ppdb.product');
        Route::get("/$prefix/product/show", 'PPDBController@showProduct')->name('ppdb.show-product');
        Route::get("/$prefix/cart", 'PPDBController@cart')->name('ppdb.cart');
        Route::get("/$prefix/custom-form/{slug}", 'PPDBController@customFormInput')->name('ppdb.custom-form.input');
        Route::post("/$prefix/custom-form/{slug}", 'PPDBController@customFormInputPost')->name('ppdb.custom-form.input.post');
        Route::get("/$prefix/detail/registration/{id?}", 'PPDBController@getPaymentRegistration')->name('ppdb.payment-registration');
        Route::post("/$prefix/repayment-registration", 'PPDBController@repaymentRegistration')->name('ppdb.repayment-registration');
        Route::get("/$prefix/get-cities", 'PPDBController@getCities')->name('ppdb.get-cities');
        Route::get("/$prefix/registration-payment-receipt/{id}", 'PPDBController@registrationPaymentReceipt')->name('ppdb.registration-payment-receipt');

        Route::prefix("/$prefix/notification")->name("ppdb.notification.")->group(function () {
            Route::get('/', 'PPDBController@notificationIndex')->name('index');
            Route::get('/{notification}/mark-read', 'PPDBController@notificationMarkRead')->name('mark-read');
            Route::get('/{notification}/delete', 'PPDBController@notificationDelete')->name('delete');
        });

        Route::prefix("/$prefix/bills")->name('ppdb.bills.')->group(function () {
            Route::get('/choise-payment', 'PPDBPaymentController@choisePayment')->name('choise-payment');
            Route::post('store', 'PPDBPaymentController@store')->name('store');
            Route::get('/payment-now', 'PPDBPaymentController@paymentNow')->name('payment-now');
            Route::get('/payment-cancel', 'PPDBPaymentController@paymentCancel')->name('payment-cancel');
            Route::get('/payment-paid-receipt', 'PPDBPaymentController@developmentPaymentReceipt')->name('payment-paid-receipt');
        });



        Route::prefix("/$prefix/embed-product")->name('ppdb.embed-product.')->group(function () {
            Route::get('/', 'PPDBController@embedProduct')->name('index');
            Route::get('/cart', 'PPDBController@embedProductCart')->name('cart');
            Route::post('/post-fitting', 'PPDBController@postFitting')->name('post-fitting');
            Route::post('/post-product', 'PPDBController@postProduct')->name('post-product');
            Route::post('/post-cart', 'PPDBController@postCart')->name('post-cart');
            Route::post('/post-voucher', 'PPDBController@postVoucher')->name('post-voucher');
            Route::post('/delete-cart', 'PPDBController@deleteCart')->name('delete-cart');
            Route::post('/cancel-order', 'PPDBController@cancelOrder')->name('cancel-order');
            Route::post('/delete-voucher', 'PPDBController@deleteVoucher')->name('delete-voucher');
            Route::get('/order-list', 'PPDBController@getOrderList')->name('order-list');
            Route::get('/order/{id?}', 'PPDBController@getOrder')->name('order');
            Route::get('/order/{id}/pdf', 'PPDBController@showPdf')->name('order.pdf');
            Route::get('/{id}', 'PPDBController@embedProductDetail')->name('detail');
            Route::post('/upload-order-confirmation', 'PPDBController@uploadOrderConfirmation')->name('upload-order-confirmation');
            Route::get("/detail-payment/{id?}", 'PPDBController@embedDetailPayment')->name('detail-payment');
            Route::post('/detail-voucher', 'PPDBController@detailVoucher')->name('detail-voucher');
            Route::post('/post-cancel-order', 'PPDBController@postCancelOrder')->name('post-cancel-order');
            Route::get('/complaint/{id}', 'PPDBController@complaint')->name('complaint');
            Route::post('/complaint-store', 'PPDBController@complaintStore')->name('complaint-store');
            Route::get('/fetch-product-order/{id}', 'PPDBController@fetchProductOrder')->name('fetch-product-order');
            Route::get('/cancel-complaint/{id}', 'PPDBController@cancelComplaint')->name('cancel-complaint');
            Route::get('/complaint/{id}/pdf', 'PPDBController@showComplaintPdf')->name('complaint.pdf');
        });
    });

    Route::get('forgot-password', function (Request $request) {
        return view('ppdb-online.forgot-password', ['request' => $request]);
    })->name('ppdb.forgot-password');
});

Route::group(['domain' => $routeService->getBackendSubdomain()], function () use ($helper) {
    Route::get('/images/{file?}', 'ImageController@getImage')->name('show_image')->where('file', '.*');
    Route::get('/file/{file?}', 'FileController@getFile')->name('show_file')->where('file', '.*');
    Route::get('/exports/{file?}', 'FileController@getExport')->name('show_export')->where('file', '.*');
    Route::post('/temp-image', 'ImageController@uploadTempImage')->name('upload_temp_image');
    Route::post('/upload-image', 'ImageController@uploadImage')->name('upload_image');
    Route::get('/imports/{file?}', 'FileController@getImport')->name('show_import')->where('file', '.*');

    //SANMARU DASHBOARD
    Route::get('/', 'AuthController@landing')->name('login');
    Route::post('/login', 'AuthController@login')->name('login.submit');
    Route::get('/logout', 'AuthController@logout')->name('logout');
    Route::group(['middleware' => 'auth:siswa'], function () {
        Route::get('/welcome', 'DashboardController@welcome')->name('welcome');

        Route::prefix("embed-product")->name('embed-product')->group(function () {
            Route::get('/', 'ShopController@embedProduct');
            Route::get('/cart', 'ShopController@embedProductCart')->name('.cart');
            Route::post('/post-fitting', 'ShopController@postFitting')->name('.post-fitting');
            Route::post('/post-product', 'ShopController@postProduct')->name('.post-product');
            Route::post('/post-cart', 'ShopController@postCart')->name('.post-cart');
            Route::post('/post-voucher', 'ShopController@postVoucher')->name('.post-voucher');
            Route::post('/delete-cart', 'ShopController@deleteCart')->name('.delete-cart');
            Route::post('/cancel-order', 'ShopController@cancelOrder')->name('.cancel-order');
            Route::post('/delete-voucher', 'ShopController@deleteVoucher')->name('.delete-voucher');
            Route::get('/order-list', 'ShopController@getOrderList')->name('.order-list');
            Route::get('/order/{id?}', 'ShopController@getOrder')->name('.order');
            Route::get('/order/{id}/pdf', 'ShopController@showPdf')->name('.order.pdf');
            Route::get('/{id}', 'ShopController@embedProductDetail')->name('.detail');
            Route::post('/upload-order-confirmation', 'ShopController@uploadOrderConfirmation')->name('.upload-order-confirmation');
            Route::get("/detail-payment/{id?}", 'ShopController@embedDetailPayment')->name('.detail-payment');
            Route::post('/detail-voucher', 'ShopController@detailVoucher')->name('.detail-voucher');
            Route::post('/post-cancel-order', 'ShopController@postCancelOrder')->name('.post-cancel-order');
            Route::get('/complaint/{id}', 'ShopController@complaint')->name('.complaint');
            Route::post('/complaint-store', 'ShopController@complaintStore')->name('.complaint-store');
            Route::get('/fetch-product-order/{id}', 'ShopController@fetchProductOrder')->name('.fetch-product-order');
            Route::get('/cancel-complaint/{id}', 'ShopController@cancelComplaint')->name('.cancel-complaint');
            Route::get('/complaint/{id}/pdf', 'ShopController@showComplaintPdf')->name('.complaint.pdf');
        });

        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::get('/profile/change-password', 'ProfileController@changePassword')->name('profile.change-password.form');
        Route::post('/profile/change-password', 'ProfileController@changePasswordSubmit')->name('profile.change-password.submit');
    });

    //ADMINISTRATOR AUTH
    Route::prefix('administrator')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('administrator.welcome');
        })->name('root');
        Route::group(['middleware' => 'web'], function () {
            Route::get('login', 'Auth\LoginController@login')->name('login');
            Route::post('login', 'Auth\LoginController@authenticate')->name('login.post');
            Route::any('logout', 'Auth\LoginController@logout')->name('logout');
        });
    });

    Route::group(['middleware' => ['web', 'auth', 'author_editor']], function () use ($helper) {
        Route::get('/administrator/dashboard-analytic', 'Admin\DashboardController@analytics')->name('admin.dashboard.analytic');

        // BLOG KATEGORI
        Route::prefix('administrator/blog-category')->name('admin.blog-category.')->namespace('Admin')->group(function () {
            Route::get('', 'BlogCategoriesController@index')->name('index');
            Route::get('add', 'BlogCategoriesController@add')->name('add');
            Route::post('insert', 'BlogCategoriesController@insert')->name('insert');
            Route::get('edit/{blogCategory}', 'BlogCategoriesController@edit')->name('edit');
            Route::patch('update/{blogCategory}', 'BlogCategoriesController@update')->name('update');
            Route::delete('delete/{blogCategory}', 'BlogCategoriesController@delete')->name('delete');
        });

        // BLOG
        Route::prefix('administrator/blog')->name('admin.blog.')->namespace('Admin')->group(function () {
            Route::get('', 'BlogController@index')->name('index');
            Route::get('add', 'BlogController@add')->name('add');
            Route::post('insert', 'BlogController@insert')->name('insert');
            Route::get('edit/{blog}', 'BlogController@edit')->name('edit');
            Route::patch('update/{blog}', 'BlogController@update')->name('update');
            Route::delete('delete/{blog}', 'BlogController@delete')->name('delete');
        });

        // HEADLINE
        Route::prefix('administrator/headline')->name('admin.headline.')->namespace('Admin')->group(function () {
            Route::get('', 'HeadlineController@index')->name('index');
            Route::get('add', 'HeadlineController@add')->name('add');
            Route::post('insert', 'HeadlineController@insert')->name('insert');
            Route::get('edit/{headline}', 'HeadlineController@edit')->name('edit');
            Route::patch('update/{headline}', 'HeadlineController@update')->name('update');
            Route::delete('delete/{headline}', 'HeadlineController@delete')->name('delete');
            Route::get('toggle/{id}', 'HeadlineController@toggle')->name('toggle');
        });

        // ABOUT & ABOUT CATEGORY
        Route::prefix('administrator/about')->name('admin.about.')->namespace('Admin')->group(function () {
            Route::get('', 'AboutController@selectCategory')->name('select-category');

            Route::get('category', 'AboutCategoryController@index')->name('category.index');
            Route::get('category/add', 'AboutCategoryController@add')->name('category.add');
            Route::post('category/insert', 'AboutCategoryController@insert')->name('category.insert');
            Route::get('category/edit/{aboutCategory}', 'AboutCategoryController@edit')->name('category.edit');
            Route::patch('category/update/{aboutCategory}', 'AboutCategoryController@update')->name('category.update');
            Route::delete('category/delete/{aboutCategory}', 'AboutCategoryController@delete')->name('category.delete');

            Route::post('category/order', 'AboutCategoryController@updateOrder')->name('category.order');

            Route::get('{aboutCategory}', 'AboutController@index')->name('index');

            Route::get('{aboutCategory}/add', 'AboutController@add')->name('add');
            Route::post('{aboutCategory}/insert', 'AboutController@insert')->name('insert');
            Route::get('{aboutCategory}/edit/{about}', 'AboutController@edit')->name('edit');
            Route::patch('{aboutCategory}/update/{about}', 'AboutController@update')->name('update');
            Route::delete('{aboutCategory}/delete/{about}', 'AboutController@delete')->name('delete');
        });

        // SCHOOL LIFE & SCHOOL LIFE CATEGORY
        Route::prefix('administrator/school-life')->name('admin.school-life.')->namespace('Admin')->group(function () {
            Route::get('', 'SchoolLifeController@selectCategory')->name('select-category');

            Route::get('category', 'SchoolLifeCategoryController@index')->name('category.index');
            Route::get('category/add', 'SchoolLifeCategoryController@add')->name('category.add');
            Route::post('category/insert', 'SchoolLifeCategoryController@insert')->name('category.insert');
            Route::get('category/edit/{schoolLifeCategory}', 'SchoolLifeCategoryController@edit')->name('category.edit');
            Route::patch('category/update/{schoolLifeCategory}', 'SchoolLifeCategoryController@update')->name('category.update');
            Route::delete('category/delete/{schoolLifeCategory}', 'SchoolLifeCategoryController@delete')->name('category.delete');

            Route::post('category/order', 'SchoolLifeCategoryController@updateOrder')->name('category.order');

            Route::get('{schoolLifeCategoryId}', 'SchoolLifeController@index')->name('index');

            Route::get('{schoolLifeCategoryId}/add', 'SchoolLifeController@add')->name('add');
            Route::post('{schoolLifeCategory}/insert', 'SchoolLifeController@insert')->name('insert');
            Route::get('{schoolLifeCategory}/edit/{schoolLife}', 'SchoolLifeController@edit')->name('edit');
            Route::patch('{schoolLifeCategory}/update/{schoolLife}', 'SchoolLifeController@update')->name('update');
            Route::delete('{schoolLifeCategory}/delete/{schoolLife}', 'SchoolLifeController@delete')->name('delete');
        });

        // TESTIMONIAL
        Route::prefix('administrator/testimonial')->name('admin.testimonial.')->namespace('Admin')->group(function () {
            Route::get('', 'TestimonialController@index')->name('index');
            Route::get('add', 'TestimonialController@add')->name('add');
            Route::post('insert', 'TestimonialController@insert')->name('insert');
            Route::get('edit/{testimonial}', 'TestimonialController@edit')->name('edit');
            Route::patch('update/{testimonial}', 'TestimonialController@update')->name('update');
            Route::delete('delete/{testimonial}', 'TestimonialController@delete')->name('delete');
            Route::get('toggle/{id}', 'TestimonialController@toggle')->name('toggle');
        });

        // VOICE OF SANMAR
        Route::prefix('administrator/voice-of-sanmar')->name('admin.voice-of-sanmar.')->namespace('Admin')->group(function () {
            Route::get('', 'VoiceOfSanmarController@index')->name('index');
            Route::get('add', 'VoiceOfSanmarController@add')->name('add');
            Route::post('insert', 'VoiceOfSanmarController@insert')->name('insert');
            Route::get('edit/{voiceOfSanmar}', 'VoiceOfSanmarController@edit')->name('edit');
            Route::patch('update/{voiceOfSanmar}', 'VoiceOfSanmarController@update')->name('update');
            Route::delete('delete/{voiceOfSanmar}', 'VoiceOfSanmarController@delete')->name('delete');
        });

        // Gallery
        Route::prefix('administrator/gallery')->name('admin.gallery.')->namespace('Admin')->group(function () {
            Route::get('', 'GalleryController@index')->name('index');
            Route::get('show/{gallery}', 'GalleryController@show')->name('show');
            Route::get('add', 'GalleryController@add')->name('add');
            Route::post('insert', 'GalleryController@insert')->name('insert');
            Route::get('edit/{gallery}', 'GalleryController@edit')->name('edit');
            Route::patch('update/{gallery}', 'GalleryController@update')->name('update');
            Route::delete('delete/{gallery}', 'GalleryController@delete')->name('delete');

            Route::get('toggle/{id}', 'GalleryController@toggle')->name('toggle');
        });

        // CAMPUS
        Route::prefix('administrator/campus')->name('admin.campus.')->namespace('Admin')->group(function () {
            Route::get('', 'CampusController@select')->name('select');

            Route::get('manage', 'CampusController@index')->name('index');
            Route::get('add', 'CampusController@add')->name('add');
            Route::post('insert', 'CampusController@insert')->name('insert');
            Route::get('edit/{campus}', 'CampusController@edit')->name('edit');
            Route::patch('update/{campus}', 'CampusController@update')->name('update');
            Route::delete('delete/{campus}', 'CampusController@delete')->name('delete');

            Route::get('{campus}/unit', 'CampusUnitController@index')->name('unit.index');

            Route::get('{campus}/unit/add', 'CampusUnitController@add')->name('unit.add');
            Route::post('{campus}/unit/insert', 'CampusUnitController@insert')->name('unit.insert');
            Route::get('{campus}/unit/edit/{campusUnit}', 'CampusUnitController@edit')->name('unit.edit');
            Route::patch('{campus}/unit/update/{campusUnit}', 'CampusUnitController@update')->name('unit.update');
            Route::delete('{campus}/unit/delete/{campusUnit}', 'CampusUnitController@delete')->name('unit.delete');
        });

        // SCHOLARSHIP
        Route::prefix('administrator/scholarship')->name('admin.scholarship.')->namespace('Admin')->group(function () {
            Route::get('', 'ScholarshipController@index')->name('index');
            Route::get('add', 'ScholarshipController@add')->name('add');
            Route::post('insert', 'ScholarshipController@insert')->name('insert');
            Route::get('edit/{scholarship}', 'ScholarshipController@edit')->name('edit');
            Route::patch('update/{scholarship}', 'ScholarshipController@update')->name('update');
            Route::delete('delete/{scholarship}', 'ScholarshipController@delete')->name('delete');
            Route::get('toggle/{id}', 'ScholarshipController@toggle')->name('toggle');
            Route::get('show/{scholarship}', 'ScholarshipController@show')->name('show');
        });

        // FAQ
        Route::prefix('administrator/faq')->name('admin.faq.')->namespace('Admin')->group(function () {
            Route::get('', 'FaqController@index')->name('index');
            Route::get('add', 'FaqController@add')->name('add');
            Route::post('insert', 'FaqController@insert')->name('insert');
            Route::get('edit/{faq}', 'FaqController@edit')->name('edit');
            Route::patch('update/{faq}', 'FaqController@update')->name('update');
            Route::delete('delete/{faq}', 'FaqController@delete')->name('delete');
            Route::get('toggle/{id}', 'FaqController@toggle')->name('toggle');
            Route::get('show/{faq}', 'FaqController@show')->name('show');
        });

        //USER ACTIVITY
        Route::get('/administrator/user-activity', 'Admin\UserActivityController@index')->name('admin.user-activity.index');

        // POPUP
        Route::prefix('administrator/popup')->name('admin.popup.')->namespace('Admin')->group(function () {
            Route::get('', 'PopupController@index')->name('index');
            Route::get('add', 'PopupController@add')->name('add');
            Route::post('insert', 'PopupController@insert')->name('insert');
            Route::get('edit/{id}', 'PopupController@edit')->name('edit');
            Route::patch('update/{id}', 'PopupController@update')->name('update');
            Route::delete('delete/{id}', 'PopupController@delete')->name('delete');
        });

        // FACILITY CATEGORY
        Route::prefix('administrator/facility-category')->name('admin.facility-category.')->namespace('Admin')->group(function () {
            Route::get('', 'FacilityCategoryController@index')->name('index');
            Route::get('add', 'FacilityCategoryController@add')->name('add');
            Route::post('insert', 'FacilityCategoryController@insert')->name('insert');
            Route::get('edit/{id}', 'FacilityCategoryController@edit')->name('edit');
            Route::patch('update/{id}', 'FacilityCategoryController@update')->name('update');
            Route::delete('delete/{id}', 'FacilityCategoryController@delete')->name('delete');
        });

        // FACILITY
        Route::prefix('administrator/facility')->name('admin.facility.')->namespace('Admin')->group(function () {
            Route::get('', 'FacilityController@index')->name('index');
            Route::get('add', 'FacilityController@add')->name('add');
            Route::post('insert', 'FacilityController@insert')->name('insert');
            Route::get('edit/{id}', 'FacilityController@edit')->name('edit');
            Route::patch('update/{id}', 'FacilityController@update')->name('update');
            Route::delete('delete/{id}', 'FacilityController@delete')->name('delete');
            Route::get('gallery-data', 'FacilityController@galleryData')->name('gallery-data');
            Route::post('gallery-data', 'FacilityController@insertGallery')->name('gallery-data.insert');
        });
    });

    Route::group(['middleware' => ['web', 'auth', 'super_admin']], function () {
        Route::get('/administrator/deploy', 'Admin\DeploymentController@index')->name('admin.deploy.index');
    });

    Route::group(['middleware' => ['web', 'auth', 'shop']], function () use ($helper) {
        // DASHBOARD ORDER
        Route::get('/administrator/dashboard-order', 'Admin\DashboardController@order')->name('admin.dashboard-order.index');
        // PRODUCT ORDER
        Route::prefix('administrator/product-order')->name('admin.product-order.')->namespace('Admin')->group(function () use ($helper) {
            Route::get('/', 'ProductOrderController@index')->name('index');
            Route::get('/add', 'ProductOrderController@add')->name('add');
            Route::get('/show/{productOrder}', 'ProductOrderController@show')->name('show');
            Route::post('/insert', 'ProductOrderController@insert')->name('insert');
            Route::get('/edit/{id}', 'ProductOrderController@edit')->name('edit');
            Route::patch('/update/{id}', 'ProductOrderController@update')->name('update');
            Route::delete('/delete/{productOrder}', 'ProductOrderController@delete')->name('delete');
            Route::get('/export', 'ProductOrderController@export')->name('export');
            Route::get('/export/kantin', 'ProductOrderController@exportKantin')->name('export.kantin');

            Route::get('/product-detail/{product}', 'ProductOrderController@productDetail')->name('product-detail');
            Route::get('/confirm-payment/{id}', 'ProductOrderController@confirmPayment')->name('confirm-payment');
            Route::post('/reject-payment/{id}', 'ProductOrderController@rejectPayment')->name('reject-payment');
            Route::post('/upload-payment/{id}', 'ProductOrderController@uploadPayment')->name('upload-payment');
            Route::get('/unit-student/{unitId}', 'ProductOrderController@unitStudent')->name('unit-student');
            Route::put('/cancel-pickup/{id}', 'ProductOrderController@cancelPickup')->name('cancel-pickup');


            if ($helper->isVaBcaEnable()) {
//                Route::get('/check-status-payment/{id}', 'ProductOrderController@checkStatusPayment')->name('check-status-payment');
                Route::get('/check-inquiry-status/{id}', 'ProductOrderController@checkInquiryStatus')->name('check-inquiry-status');
            }

            Route::get('/export-list', 'ProductOrderController@exportList')->name('export-list');

            Route::get('/send-confirmed', 'ProductOrderController@sendConfirmed')->name('send-confirmed');
            Route::get('/student-data/{userId}/{type}', 'ProductOrderController@studentData')->name('student-data');
        });

        //PRODUCT ORDER KANTIN
        Route::prefix('administrator/product-order/kantin')->name('admin.product-order.kantin.')->group(function () {
            Route::get('create', 'Admin\ProductOrderKantinController@create')->name('create');
            Route::post('store', 'Admin\ProductOrderKantinController@store')->name('store');
            Route::get('edit/{id}', 'Admin\ProductOrderKantinController@edit')->name('edit');
            Route::put('update/{id}', 'Admin\ProductOrderKantinController@update')->name('update');
            Route::get('{id}', 'Admin\ProductOrderKantinController@show')->name('show');
        });

        // PRODUCT VOUCHERS
        Route::prefix('administrator/voucher')->name('admin.voucher.')->namespace('Admin')->group(function () {
            Route::get('', 'VoucherController@index')->name('index');
            Route::get('add', 'VoucherController@add')->name('add');
            // Route::get('add-voucher', 'VoucherController@addNewVoucher')->name('add-voucher');
            Route::get('add-voucher', 'VoucherController@addVoucher')->name('add-voucher');
            Route::post('insert', 'VoucherController@insert')->name('insert');
            Route::post('new-insert', 'VoucherController@newInsert')->name('new-insert');
            Route::get('edit/{fitting}', 'VoucherController@edit')->name('edit');
            Route::patch('update/{fitting}', 'VoucherController@update')->name('update');
            Route::patch('new-update/{fitting}', 'VoucherController@newUpdate')->name('new-update');
            Route::delete('delete/{fitting}', 'VoucherController@delete')->name('delete');
            Route::get('ajax', 'VoucherController@ajax')->name('ajax');
            Route::get('usage-miss', 'VoucherController@usageMiss')->name('usage-miss');
            Route::get('usage', 'VoucherController@usage')->name('usage');
            Route::get('usage-voucher', 'VoucherController@usageVoucher')->name('usage-voucher');
            Route::get('fetch-student', 'VoucherController@fetchStudent')->name('fetch-student');
            Route::get('modal-generate-voucher-development', 'VoucherController@modalGenerateVoucherDevelopment')->name('modal-generate-voucher-development');
            Route::get('generate-voucher-development', 'VoucherController@generateVoucherDevelopment')->name('generate-voucher-development');
            Route::get('export-usage', 'VoucherController@exportUsage')->name('export-usage');
            Route::get('export-new-usage', 'VoucherController@exportNewUsage')->name('export-new-usage');
            Route::get('export-usage-miss', 'VoucherController@exportUsageMiss')->name('export-usage-miss');
            Route::get('detail-receive-voucher/{id}', 'VoucherController@detailReceiveVoucher')->name('detail-receive-voucher');
        });
        // PRODUCT FITTING
        Route::prefix('administrator/fitting')->name('admin.fitting.')->namespace('Admin')->group(function () {
            Route::get('', 'FittingController@index')->name('index');
            Route::get('add', 'FittingController@add')->name('add');
            Route::post('insert', 'FittingController@insert')->name('insert');
            Route::get('edit/{fitting}', 'FittingController@edit')->name('edit');
            Route::patch('update/{fitting}', 'FittingController@update')->name('update');
            Route::delete('delete/{fitting}', 'FittingController@delete')->name('delete');
        });
        //VENDOR
        Route::get('/administrator/vendor', 'Admin\VendorController@index')->name('admin.vendor.index');
        Route::get('/administrator/vendor/add', 'Admin\VendorController@add')->name('admin.vendor.add');
        Route::post('/administrator/vendor/insert', 'Admin\VendorController@insert')->name('admin.vendor.insert');
        Route::get('/administrator/vendor/edit/{id}', 'Admin\VendorController@edit')->name('admin.vendor.edit');
        Route::post('/administrator/vendor/update/{id}', 'Admin\VendorController@update')->name('admin.vendor.update');
        Route::get('/administrator/vendor/delete/{id}', 'Admin\VendorController@delete')->name('admin.vendor.delete');
        Route::get('/administrator/vendor/export', 'Admin\VendorController@export')->name('admin.vendor.export');
        Route::post('/administrator/vendor/import', 'Admin\VendorController@import')->name('admin.vendor.import');
        //PRODUCT
        Route::get('/administrator/product', 'Admin\ProductController@index')->name('admin.product.index');
        Route::get('/administrator/product/add', 'Admin\ProductController@add')->name('admin.product.add');
        Route::post('/administrator/product/insert', 'Admin\ProductController@insert')->name('admin.product.insert');
        Route::get('/administrator/product/edit/{id}', 'Admin\ProductController@edit')->name('admin.product.edit');
        Route::post('/administrator/product/update/{id}', 'Admin\ProductController@update')->name('admin.product.update');
        Route::get('/administrator/product/delete/{id}', 'Admin\ProductController@delete')->name('admin.product.delete');
        Route::get('/administrator/product/export', 'Admin\ProductController@export')->name('admin.product.export');
        Route::post('/administrator/product/import', 'Admin\ProductController@import')->name('admin.product.import');
        Route::get('/administrator/product/toggle/{id}', 'Admin\ProductController@toggle')->name('admin.product.toggle');
        Route::get('/administrator/product/history-stock', 'Admin\ProductController@historyStock')->name('admin.product.history-stock');
        Route::get('/administrator/product/{id}', 'Admin\ProductController@show')->name('admin.product.show');

        //PRODUCT ACCEPTANCE
        Route::get('/administrator/product-acceptance', 'Admin\ProductAcceptanceController@index')->name('admin.product-acceptance.index');
        Route::get('/administrator/product-acceptance/add', 'Admin\ProductAcceptanceController@add')->name('admin.product-acceptance.add');
        Route::post('/administrator/product-acceptance/find-by-product', 'Admin\ProductAcceptanceController@findByProduct')->name('admin.product-acceptance.find-by-product');
        Route::post('/administrator/product-acceptance/store', 'Admin\ProductAcceptanceController@store')->name('admin.product-acceptance.store');
        Route::get('/administrator/product-acceptance/show/{id}', 'Admin\ProductAcceptanceController@show')->name('admin.product-acceptance.show');
        Route::get('/administrator/product-acceptance/uniform', 'Admin\ProductAcceptanceController@uniform')->name('admin.product-acceptance.uniform');

        Route::prefix('administrator/product/kantin')->name('admin.product.kantin.')->group(function () {
            Route::get('create', 'Admin\ProductKantinController@create')->name('create');
            Route::post('store', 'Admin\ProductKantinController@store')->name('store');
            Route::get('edit/{id}', 'Admin\ProductKantinController@edit')->name('edit');
            Route::put('update/{id}', 'Admin\ProductKantinController@update')->name('update');
            Route::get('{id}', 'Admin\ProductKantinController@show')->name('show');
        });

        // CHECK PRODUCT ORDERPAYMENT
        Route::prefix('administrator/uniform-payment')->name('admin.uniform-payment.')->namespace('Admin')->group(function () {
            Route::get('', 'UniformPaymentController@index')->name('index');
            Route::post('import', 'UniformPaymentController@import')->name('import');
            Route::post('store', 'UniformPaymentController@store')->name('store');

            Route::get('history', 'UniformPaymentController@history')->name('history');
            Route::get('history-detail/{importJobId}', 'UniformPaymentController@detailHistory')->name('detail-history');
        });

        Route::prefix('administrator/uniform-deadline')->name('admin.uniform-deadline.')->namespace('Admin')->group(function () {
            Route::get('', 'UniformDeadlineController@index')->name('index');
            Route::get('add', 'UniformDeadlineController@add')->name('add');
            Route::post('insert', 'UniformDeadlineController@store')->name('insert');
            Route::get('/edit/{id}', 'UniformDeadlineController@edit')->name('edit');
            Route::post('/update/{id}', 'UniformDeadlineController@update')->name('update');
            Route::get('/delete/{id}', 'UniformDeadlineController@delete')->name('delete');

        });

        Route::prefix('administrator/distribution-order')->name('admin.distribution-order.')->namespace('Admin')->group(function () {
            Route::get('', 'DistributionOrdersController@index')->name('index');
            Route::get('add', 'DistributionOrdersController@add')->name('add');
            Route::post('insert', 'DistributionOrdersController@store')->name('insert');
            Route::get('/send/{id}', 'DistributionOrdersController@send')->name('send');
            Route::get('/confirm/{id}', 'DistributionOrdersController@confirm')->name('confirm');
            Route::get('/delete/{id}', 'DistributionOrdersController@delete')->name('delete');
            Route::get('/export/{id}', 'DistributionOrdersController@export')->name('export');
            Route::get('/pdf/{id}', 'DistributionOrdersController@pdf')->name('pdf');
            Route::post('find_uniform_order', 'DistributionOrdersController@findUniformOrder')->name('find_uniform_order');

        });

        Route::prefix('administrator/complaint')->name('admin.complaint.')->namespace('Admin')->group(function () {
            Route::get('', 'ComplaintOrdersController@index')->name('index');
            Route::get('show/{id}', 'ComplaintOrdersController@show')->name('show');
            Route::post('change-status', 'ComplaintOrdersController@changeStatus')->name('change-status');
            Route::post('setting-period', 'ComplaintOrdersController@settingPeriod')->name('setting-period');

        });

        // PRODUCT ORDER PICKUP
        Route::prefix('administrator/product-order-pickup')->name('admin.product-order-pickup.')->namespace('Admin')->group(function () {
            Route::get('/', 'ProductOrderPickupController@index')->name('index');
            Route::put('/pickup/{id}', 'ProductOrderPickupController@pickup')->name('pickup');
            Route::get('/show/{id}', 'ProductOrderPickupController@show')->name('show');
            Route::put('/upload-pickup-image/{id}', 'ProductOrderPickupController@uploadPickupImage')->name('upload-pickup-image');
            Route::get('/send-confirmation/{id}', 'ProductOrderPickupController@sendConfirmation')->name('send-confirmation');
            Route::get('/send-pickup-confirmation', 'ProductOrderPickupController@sendPickupConfirmation')->name('send-pickup-confirmation');
            Route::get('/export', 'ProductOrderPickupController@export')->name('export');
            Route::put('/cancel-pickup/{id}', 'ProductOrderPickupController@cancelPickup')->name('cancel-pickup');
            Route::get('/schedule', 'ProductOrderPickupController@createSchedule')->name('create-schedule');
            Route::post('/schedule', 'ProductOrderPickupController@storeSchedule')->name('store-schedule');
            Route::post('/reset-schedule/{productOrder}', 'ProductOrderPickupController@resetSchedule')->name('reset-schedule');
            Route::get('/qr-result/{id}', 'ProductOrderPickupController@showQrResult')->name('qr-result');
            Route::get('/fetch-period', 'ProductOrderPickupController@fetchPeriod')->name('fetch-period');
        });

        // UNIFORM OVERPAYMENT
        Route::prefix('administrator/uniform-overpayment')->name('admin.uniform-overpayment.')->namespace('Admin')->group(function () {
            Route::get('', 'UniformOverpaymentController@index')->name('index');
            Route::get('add', 'UniformOverpaymentController@add')->name('add');
            Route::post('insert', 'UniformOverpaymentController@insert')->name('insert');
            Route::get('student-data', 'UniformOverpaymentController@studentData')->name('student-data');
            Route::get('show/{id}',
                'UniformOverpaymentController@show'
            )->name('show');
            Route::get('edit/{id}',
                'UniformOverpaymentController@edit'
            )->name('edit');
            Route::put('update/{id}', 'UniformOverpaymentController@update')->name('update');
        });
    });

    // ROUTE FOR SHOP THAT CAN ACCESSED BY ADMIN PPDB TOO
    Route::group(['middleware' => ['web', 'auth', 'ppdb_shop']], function () {
        // PRODUCT ORDER
        Route::prefix('administrator/product-order')->name('admin.product-order.')->namespace('Admin')->group(function () {
            Route::get('/show/{productOrder}', 'ProductOrderController@show')->name('show');
            Route::prefix('report')->name('report.')->group(function () {
                Route::get('/', 'ReportProductOrderController@index')->name('index');
                Route::get('/purchase-report', 'ReportProductOrderController@purchaseReport')->name('purchase-report');
                Route::get('/export', 'ReportProductOrderController@export')->name('export');
                Route::get('/export-purchase-report', 'ReportProductOrderController@exportPurchaseReport')->name('export-purchase-report');
                Route::get('/fetch-purchase-report', 'ReportProductOrderController@fetchPurchaseReport')->name('fetch-purchase-report');
            });
        });
    });

    Route::group(['middleware' => ['web', 'auth', 'admin']], function () {
        //USER
        Route::get('/administrator/user', 'Admin\UserController@index')->name('admin.user.index');
        Route::get('/administrator/user/add', 'Admin\UserController@add')->name('admin.user.add')->middleware(['web', 'auth', 'super_admin']);
        Route::post('/administrator/user/insert', 'Admin\UserController@insert')->name('admin.user.insert')->middleware(['web', 'auth', 'super_admin']);
        Route::get('/administrator/user/edit/{id}', 'Admin\UserController@edit')->name('admin.user.edit')->middleware(['web', 'auth', 'super_admin']);
        Route::post('/administrator/user/update/{id}', 'Admin\UserController@update')->name('admin.user.update')->middleware(['web', 'auth', 'super_admin']);
        Route::get('/administrator/user/delete/{id}', 'Admin\UserController@delete')->name('admin.user.delete')->middleware(['web', 'auth', 'super_admin']);
        Route::get('/administrator/user/export', 'Admin\UserController@export')->name('admin.user.export');
        Route::post('/administrator/user/import', 'Admin\UserController@import')->name('admin.user.import');
        //TEACHER
        Route::get('/administrator/teacher', 'Admin\TeacherController@index')->name('admin.teacher.index');
        Route::get('/administrator/teacher/add', 'Admin\TeacherController@add')->name('admin.teacher.add');
        Route::post('/administrator/teacher/insert', 'Admin\TeacherController@insert')->name('admin.teacher.insert');
        Route::get('/administrator/teacher/edit/{id}', 'Admin\TeacherController@edit')->name('admin.teacher.edit');
        Route::post('/administrator/teacher/update/{id}', 'Admin\TeacherController@update')->name('admin.teacher.update');
        Route::get('/administrator/teacher/delete/{id}', 'Admin\TeacherController@delete')->name('admin.teacher.delete');
        Route::get('/administrator/teacher/export', 'Admin\TeacherController@export')->name('admin.teacher.export');
        Route::post('/administrator/teacher/import', 'Admin\TeacherController@import')->name('admin.teacher.import');
        //STUDENT
        Route::get('/administrator/student', 'Admin\StudentController@index')->name('admin.student.index');
        Route::get('/administrator/student/add', 'Admin\StudentController@add')->name('admin.student.add');
        Route::post('/administrator/student/insert', 'Admin\StudentController@insert')->name('admin.student.insert');
        Route::get('/administrator/student/edit/{id}', 'Admin\StudentController@edit')->name('admin.student.edit');
        Route::post('/administrator/student/update/{id}', 'Admin\StudentController@update')->name('admin.student.update');
        Route::get('/administrator/student/show/{id}', 'Admin\StudentController@show')->name('admin.student.show');
        Route::get('/administrator/student/delete/{id}', 'Admin\StudentController@delete')->name('admin.student.delete');
        Route::get('/administrator/student/export', 'Admin\StudentController@export')->name('admin.student.export');
        Route::post('/administrator/student/import', 'Admin\StudentController@import')->name('admin.student.import');
        //COURSE
        Route::get('/administrator/course', 'Admin\CourseController@index')->name('admin.course.index');
        Route::get('/administrator/course/add', 'Admin\CourseController@add')->name('admin.course.add');
        Route::post('/administrator/course/insert', 'Admin\CourseController@insert')->name('admin.course.insert');
        Route::get('/administrator/course/edit/{course}', 'Admin\CourseController@edit')->name('admin.course.edit');
        Route::post('/administrator/course/update/{course}', 'Admin\CourseController@update')->name('admin.course.update');
        Route::get('/administrator/course/delete/{course}', 'Admin\CourseController@delete')->name('admin.course.delete');
        Route::get('/administrator/course/toggle/{course}', 'Admin\CourseController@toggle')->name('admin.course.toggle');
        Route::get('/administrator/course/export', 'Admin\CourseController@export')->name('admin.course.export');
        Route::post('/administrator/course/import', 'Admin\CourseController@import')->name('admin.course.import');
        //EVENT
        Route::get('/administrator/event', 'Admin\EventController@index')->name('admin.event.index');
        Route::get('/administrator/event/add', 'Admin\EventController@add')->name('admin.event.add');
        Route::post('/administrator/event/insert', 'Admin\EventController@insert')->name('admin.event.insert');
        Route::get('/administrator/event/edit/{id}', 'Admin\EventController@edit')->name('admin.event.edit');
        Route::post('/administrator/event/update/{id}', 'Admin\EventController@update')->name('admin.event.update');
        Route::get('/administrator/event/delete/{id}', 'Admin\EventController@delete')->name('admin.event.delete');
        Route::get('/administrator/event/toggle/{id}', 'Admin\EventController@toggle')->name('admin.event.toggle');
        //CLASS
        Route::get('/administrator/class', 'Admin\ClassController@index')->name('admin.class.index');
        Route::get('/administrator/class/add', 'Admin\ClassController@add')->name('admin.class.add');
        Route::post('/administrator/class/insert', 'Admin\ClassController@insert')->name('admin.class.insert');
        Route::get('/administrator/class/edit/{id}', 'Admin\ClassController@edit')->name('admin.class.edit');
        Route::post('/administrator/class/update/{id}', 'Admin\ClassController@update')->name('admin.class.update');
        Route::get('/administrator/class/delete/{id}', 'Admin\ClassController@delete')->name('admin.class.delete');
        Route::get('/administrator/class/export', 'Admin\ClassController@export')->name('admin.class.export');
        Route::post('/administrator/class/import', 'Admin\ClassController@import')->name('admin.class.import');
        //EXTRACURRICULAR
        Route::get('/administrator/extracurricular', 'Admin\ExtracurricularController@index')->name('admin.extracurricular.index');
        Route::get('/administrator/extracurricular/add', 'Admin\ExtracurricularController@add')->name('admin.extracurricular.add');
        Route::post('/administrator/extracurricular/insert', 'Admin\ExtracurricularController@insert')->name('admin.extracurricular.insert');
        Route::get('/administrator/extracurricular/edit/{id}', 'Admin\ExtracurricularController@edit')->name('admin.extracurricular.edit');
        Route::post('/administrator/extracurricular/update/{id}', 'Admin\ExtracurricularController@update')->name('admin.extracurricular.update');
        Route::get('/administrator/extracurricular/delete/{id}', 'Admin\ExtracurricularController@delete')->name('admin.extracurricular.delete');
        Route::get('/administrator/extracurricular/export', 'Admin\ExtracurricularController@export')->name('admin.extracurricular.export');
        Route::post('/administrator/extracurricular/import', 'Admin\ExtracurricularController@import')->name('admin.extracurricular.import');
        //GLOBAL PARAMETERS SETTING
        // Route::get('/administrator/global-parameters-setting', 'Admin\GlobalParametersSettingController@index')->name('admin.global-parameters-setting.index');
        // Route::get('/administrator/global-parameters-setting/add', 'Admin\GlobalParametersSettingController@add')->name('admin.global-parameters-setting.add');
        // FINANCE
        Route::prefix('administrator/finance')->name('admin.finance.')->namespace('Admin')->group(function () {
            Route::get('', 'FinanceController@index')->name('index');
            Route::get('add', 'FinanceController@add')->name('add');
            Route::post('insert', 'FinanceController@insert')->name('insert');
            Route::get('edit/{finance}', 'FinanceController@edit')->name('edit');
            Route::patch('update/{finance}', 'FinanceController@update')->name('update');
            Route::delete('delete/{finance}', 'FinanceController@delete')->name('delete');

            Route::get('unit-periode/{unitId}', 'FinanceController@unitPeriode')->name('unit-periode');

            Route::get('export', 'FinanceController@export')->name('export');
            Route::post('import', 'FinanceController@import')->name('import');

            Route::get('verification', 'FinanceController@verification')->name('verification');
        });

        Route::prefix('administrator/system-configuration')->name('admin.system-configuration.')->namespace('Admin')->group(function () {
           Route::get('/', 'FinanceSystemConfigurationController@index')->name('index');
           Route::get('/add', 'FinanceSystemConfigurationController@add')->name('add');
           Route::post('/store', 'FinanceSystemConfigurationController@store')->name('store');
           Route::get('/update', 'FinanceSystemConfigurationController@update')->name('update');
        });

        Route::prefix('administrator/dispensation')->name('admin.dispensation.')->namespace('Admin')->group(function () {
           Route::get('/', 'PaymentDispensationController@index')->name('index');
           Route::get('/add', 'PaymentDispensationController@add')->name('add');
           Route::post('/store', 'PaymentDispensationController@store')->name('store');
           Route::get('/update', 'PaymentDispensationController@update')->name('update');
           Route::get('fetch-student', 'PaymentDispensationController@fetchStudent')->name('fetch-student');
           Route::get('fetch-anual-cost', 'PaymentDispensationController@fetchAnualCost')->name('fetch-anual-cost');
        });

        // CLASS SCHEDULE
        Route::prefix('administrator/class-schedule')->name('admin.class-schedule.')->namespace('Admin')->group(function () {
            Route::get('/', 'ClassScheduleController@index')->name('index');
            Route::get('/add', 'ClassScheduleController@add')->name('add');
            Route::post('/insert', 'ClassScheduleController@insert')->name('insert');
            Route::get('/edit/{id}', 'ClassScheduleController@edit')->name('edit');
            Route::patch('/update/{id}', 'ClassScheduleController@update')->name('update');
            Route::delete('/delete/{id}', 'ClassScheduleController@delete')->name('delete');
            Route::get('/unit-class/{unitId}', 'ClassScheduleController@unitClass')->name('unit-class');
            Route::get('/calendar-data/{classId}', 'ClassScheduleController@calendarData')->name('calendar-data');
        });

        Route::prefix('administrator/report')->name('admin.report.')->namespace('Admin')->group(function () {
            Route::prefix('development-report')->name('development-report.')->group(function () {
                Route::get('/', 'DevelopmentReportController@index')->name('index');
                Route::get('/export', 'DevelopmentReportController@export')->name('export');
            });

            Route::prefix('finance-report')->name('finance-report.')->group(function () {
                Route::get('/', 'FinanceReportController@index')->name('index');
                Route::get('/export', 'FinanceReportController@export')->name('export');
            });

            Route::prefix('dispensation-report')->name('dispensation-report.')->group(function () {
                Route::get('/', 'DispensationReportController@index')->name('index');
                Route::get('/export', 'DispensationReportController@export')->name('export');
            });

            Route::prefix('payment-pppdb-report')->name('payment-ppdb-report.')->group(function () {
                Route::get('/', 'PaymentPPDBReportController@index')->name('index');
                Route::get('/export', 'PaymentPPDBReportController@export')->name('export');
            });

            Route::prefix('payment-pppdb-report')->name('payment-ppdb-report.')->group(function () {
                Route::get('/', 'PaymentPPDBReportController@index')->name('index');
                Route::get('/export', 'PaymentPPDBReportController@export')->name('export');
            });

            Route::prefix('admission-report')->name('admission-report.')->group(function () {
                Route::get('/', 'AdmissionReportController@index')->name('index');
                Route::get('/export', 'AdmissionReportController@export')->name('export');
            });

        });

        Route::prefix('administrator/dashboard-ppdb')->name('admin.dashboard-ppdb.')->namespace('Admin')->group(function () {
            Route::get('/', 'DashboardPPDBController@index')->name('index');
        });
    });

    Route::group(['middleware' => ['web', 'auth', 'ppdb']], function () {
        //PPDB
        Route::get('/administrator/ppdb', 'Admin\PPDBController@index')->name('admin.ppdb.index');

        Route::get('/administrator/ppdb/ajax', 'Admin\PPDBController@ajax')->name('admin.ppdb.ajax');
        Route::get('/administrator/ppdb/add', 'Admin\PPDBController@add')->name('admin.ppdb.add');
        Route::post('/administrator/ppdb/insert', 'Admin\PPDBController@insert')->name('admin.ppdb.insert');

        Route::get('/administrator/ppdb/edit/{id}', 'Admin\PPDBController@edit')->name('admin.ppdb.edit');
        Route::get('/administrator/ppdb/konfirmasi/{id}', 'Admin\PPDBController@confirm')->name('admin.ppdb.confirm');
        Route::get('/administrator/ppdb/konfirmasi-pembayaran/{id}', 'Admin\PPDBController@confirmPayment')->name('admin.ppdb.confirm-payment');
        Route::post('/administrator/ppdb/tolak-pembayaran/{id}', 'Admin\PPDBController@rejectPayment')->name('admin.ppdb.reject-payment');
        Route::post('/administrator/ppdb/confirm-development-statement/{id}', 'Admin\PPDBController@confirmDevelopmentStatement')->name('admin.ppdb.confirm-development-statement');
        Route::post('/administrator/ppdb/update/{id}', 'Admin\PPDBController@update')->name('admin.ppdb.update');
        Route::get('/administrator/ppdb/show/{id}', 'Admin\PPDBController@show')->name('admin.ppdb.show');
        Route::get('/administrator/ppdb/show-payment/{id}', 'Admin\PPDBController@showPayment')->name('admin.ppdb.show-payment');
        Route::post('/administrator/ppdb/send-confirmation/{id}', 'Admin\PPDBController@sendConfirmation')->name('admin.ppdb.send-confirmation')->middleware('web', 'auth', 'admin');
        Route::get('/administrator/ppdb/delete/{id}', 'Admin\PPDBController@delete')->name('admin.ppdb.delete');
        Route::get('/administrator/ppdb/export', 'Admin\PPDBController@export')->name('admin.ppdb.export');
        Route::get('/administrator/ppdb/get-development-file/{id}', 'Admin\PPDBController@getDevelopmentStatementLetterFile')->name('admin.ppdb.get-development-file');
        Route::post('/administrator/ppdb/{ppdbUser}/reset-development-payment-method/', 'Admin\PPDBController@resetDevelopmentPaymentMethod')->name('admin.ppdb.reset-development-payment-method');
        Route::post('/administrator/ppdb/{ppdbUser}/accept-student/', 'Admin\PPDBController@confirm')->name('admin.ppdb.accept');
        Route::get('/administrator/ppdb/check-inquiry-status/{id}', 'Admin\PPDBController@checkInquiryStatus')->name('admin.ppdb.check-inquiry-status');
        Route::get('/administrator/ppdb/download-template', 'Admin\PPDBController@downloadTemplate')->name('admin.ppdb.download-template');
        Route::post('/administrator/ppdb/import', 'Admin\PPDBController@import')->name('admin.ppdb.import');
        Route::post('/administrator/ppdb/close-billing', 'Admin\PPDBController@closeBilling')->name('admin.ppdb.close-billing');

        //MONITORING PPDB
        Route::prefix('administrator/ppdb-monitoring')->name('admin.ppdb-monitoring.')->namespace('Admin')->group(function () {
            Route::get('', 'PPDBMonitoringController@index')->name('index');
            Route::get('show-detail-period/{id}', 'PPDBMonitoringController@showDetailPeriod')->name('show-detail-period');
            Route::get('show-detail-stage/{id}/{type}/{stage_id}', 'PPDBMonitoringController@showDetailStage')->name('show-detail-stage');
            Route::get('users-last-stage/{id}', 'PPDBMonitoringController@userLastStage')->name('users-last-stage');
            Route::post('post-users/{id}', 'PPDBMonitoringController@postUsers')->name('post-users');
            Route::get('import-users-last-stage/{id}', 'PPDBMonitoringController@importUsers')->name('import-users-last-stage');
            Route::get('template-setting-class', 'PPDBMonitoringController@templateSettingClass')->name('template-setting-class');
            Route::post('import-users-student/{id}', 'PPDBMonitoringController@importUserStudent')->name('import-users-student');
            Route::get('sync-stage-development/{id}/{stage_id}', 'PPDBMonitoringController@syncStageDevelopment')->name('sync-stage-development');
            Route::get('set-inactive/{id}', 'PPDBMonitoringController@setInactive')->name('set-inactive');
        });

        // AGE LIMIT
        Route::prefix('administrator/age-limit')->name('admin.age-limit.')->namespace('Admin')->group(function () {
            Route::get('', 'AgeLimitController@index')->name('index');
            Route::get('add', 'AgeLimitController@add')->name('add');
            Route::post('insert', 'AgeLimitController@insert')->name('insert');
            Route::get('edit/{ageLimit}', 'AgeLimitController@edit')->name('edit');
            Route::patch('update/{ageLimit}', 'AgeLimitController@update')->name('update');
            Route::delete('delete/{ageLimit}', 'AgeLimitController@delete')->name('delete');
        });
        // PERIOD
        Route::prefix('administrator/period')->name('admin.period.')->namespace('Admin')->group(function () {
            Route::get('', 'PeriodController@index')->name('index');
            Route::get('add', 'PeriodController@add')->name('add');
            Route::post('insert', 'PeriodController@insert')->name('insert');
            Route::get('edit/{period}', 'PeriodController@edit')->name('edit');
            Route::patch('update/{period}', 'PeriodController@update')->name('update');
            Route::delete('delete/{period}', 'PeriodController@delete')->name('delete');
            Route::get('show/{period}', 'PeriodController@show')->name('show');
            Route::get('export/', 'PeriodController@export')->name('export');

            Route::get('fetch', 'PeriodController@fetch')->name('fetch');
        });
        // STAGES
        Route::prefix('administrator/stage')->name('admin.stage.')->namespace('Admin')->group(function () {
            Route::get('', 'StageController@index')->name('index');
            Route::get('add', 'StageController@add')->name('add');
            Route::post('insert', 'StageController@insert')->name('insert');
            Route::get('edit/{stage}', 'StageController@edit')->name('edit');
            Route::patch('update/{stage}', 'StageController@update')->name('update');
            Route::delete('delete/{stage}', 'StageController@delete')->name('delete');
            Route::get('get-users/{stage}/{unit?}/{period?}', 'StageController@getUsers')->name('users-json');
            Route::get('get-stage-users/{stage}/{unit?}/{period?}', 'StageController@getUsersStage')->name('users-stage-json');
            Route::get('get-periods/{unit?}', 'StageController@getPeriods')->name('get-periods');
            Route::post('post-users/{stage}', 'StageController@postUsers')->name('post-users');
            Route::post('post-mass/{stage}', 'StageController@postMass')->name('post-mass');

            Route::get('export-users/{stage}/{unit?}/{period?}', 'StageController@export')->name('export-users');
            Route::get('export-student', 'StageController@exportWithStudent')->name('export-student');
            Route::post('import-users/{stage}', 'StageController@import')->name('import-users');
            Route::post('import-users-student/{stage}', 'StageController@importStudent')->name('import-users-student');
        });

        // EXPORT DATA
        Route::get('/administrator/export-data', 'Admin\ExportDataController@index')->name('admin.export-data.index');
        Route::get('/administrator/export-data/export/{id}', 'Admin\ExportDataController@export')->name('admin.export-data.export');
        // CHECK PAYMENT
        Route::prefix('administrator/payment')->name('admin.payment.')->namespace('Admin')->group(function () {
            Route::get('', 'PaymentController@index')->name('index');
            Route::post('import', 'PaymentController@import')->name('import');
            Route::post('store', 'PaymentController@store')->name('store');

            Route::get('history', 'PaymentController@history')->name('history');
            Route::get('history-detail/{importJobId}', 'PaymentController@detailHistory')->name('detail-history');
        });

        //CHECK ORDER
        Route::prefix('administrator/check-order')->name('admin.check-order.')->namespace('Admin')->group(function () {
            Route::get('', 'CheckOrderController@index')->name('index');
            Route::get('export', 'CheckOrderController@export')->name('export');
            Route::get('dashboard', 'CheckOrderController@dashboard')->name('dashboard');
        });

        //PPDB RESIGNATION
        Route::prefix('administrator/ppdb-resignation')->name('admin.ppdb-resignation.')->namespace('Admin')->group(function () {
            Route::get('', 'PPDBResignationController@index')->name('index');
            Route::get('add', 'PPDBResignationController@add')->name('add');
            Route::get('ajax', 'PPDBResignationController@ajax')->name('ajax');
            Route::post('insert', 'PPDBResignationController@insert')->name('insert');
            Route::get('show/{id}', 'PPDBResignationController@show')->name('show');
            Route::get('edit/{id}', 'PPDBResignationController@edit')->name('edit');
            Route::post('update/{id}', 'PPDBResignationController@update')->name('update');
            Route::get('{id}/add-refund', 'PPDBResignationController@addRefund')->name('add-refund');
            Route::post('{id}/insert-refund', 'PPDBResignationController@insertRefund')->name('insert-refund');
            Route::get('{id}/show-refund/{paymentRefundId}', 'PPDBResignationController@showRefund')->name('show-refund');
        });

        //PAYMENT REFUND
        Route::prefix('administrator/payment-refund')->name('admin.payment-refund.')->namespace('Admin')->group(function () {
            Route::post('confirm/{id}', 'PaymentRefundController@confirmRefund')->name('confirm-refund');
            Route::get('order-detail/{productOrderId}', 'PaymentRefundController@orderDetail')->name('order-detail');
        });

        //UNIT
        Route::get('/administrator/unit', 'Admin\UnitController@index')->name('admin.unit.index');
        Route::get('/administrator/unit/show/{id}', 'Admin\UnitController@show')->name('admin.unit.show');
        Route::get('/administrator/unit/add', 'Admin\UnitController@add')->name('admin.unit.add');
        Route::post('/administrator/unit/insert', 'Admin\UnitController@insert')->name('admin.unit.insert');
        Route::get('/administrator/unit/edit/{id}', 'Admin\UnitController@edit')->name('admin.unit.edit');
        Route::post('/administrator/unit/update/{id}', 'Admin\UnitController@update')->name('admin.unit.update');
        Route::get('/administrator/unit/delete/{id}', 'Admin\UnitController@delete')->name('admin.unit.delete');
        Route::get('/administrator/unit/export', 'Admin\UnitController@export')->name('admin.unit.export');
        Route::post('/administrator/unit/import', 'Admin\UnitController@import')->name('admin.unit.import');

        // ERP POSTING
        Route::get('/administrator/erp-posting', 'Admin\ERPPostingController@index')->name('admin.erp-posting.index');
        Route::post('/administrator/erp-posting/init-store', 'Admin\ERPPostingController@initStore')->name('admin.erp-posting.init-store');
        Route::post('/administrator/erp-posting/{progress}', 'Admin\ERPPostingController@store')->name('admin.erp-posting.store');
        Route::get('/administrator/erp-posting/unit-periods/{unitId}', 'Admin\ERPPostingController@unitPeriods')->name('admin.erp-posting.unit-periods');

        //Notifications
        Route::prefix('administrator')->name('admin.')->group(function () {
            Route::prefix('notification')->name('notification.')->group(function () {
                Route::get('/fetch-period', 'Admin\NotificationController@fetchPeriod')->name('fetch-period');
                Route::get('/fetch-ppdb-user', 'Admin\NotificationController@fetchPpdbUser')->name('fetch-ppdb-user');
            });

            Route::resource('notification', 'Admin\NotificationController')->except([
                'edit', 'update'
            ]);

            // Custom Form
            Route::get('custom-form/get-periods/{unit?}', 'Admin\CustomFormController@getPeriods')->name('custom_form.get-periods');
            Route::resource('custom-form', 'Admin\CustomFormController')->names('custom_form');
            Route::get('custom-form/export/{id?}', 'Admin\CustomFormController@export')->name('custom_form.export');
        });


        // PPDB CHECK EXCESS DATE DEVELOPMENT
        Route::get('/administrator/ppdb-check-excess-date', 'Admin\PPDBCheckExcessDateController@index')->name('admin.ppdb-check-excess-date.index');
        Route::get('/administrator/ppdb-check-excess-date/unit-periods/{unitId}', 'Admin\PPDBCheckExcessDateController@unitPeriods')->name('admin.ppdb-check-excess-date.unit-periods');
        Route::post('/administrator/ppdb-check-excess-date', 'Admin\PPDBCheckExcessDateController@check')->name('admin.ppdb-check-excess-date.check');
    });

    Route::group(['middleware' => ['web', 'auth']], function () {
        Route::get('/administrator/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
    });
});

Route::group(['domain' => $routeService->getWebSubdomain()], function () use ($routeService) {
    $prefix = 'web';
    if ($routeService->isProduction()) {
        $prefix = '';
    }

    //PPDB ONLINE
    Route::group(['middleware' => 'web'], function () use ($prefix) {
        Route::get($prefix . '/', 'Front\HomeController@index')->name('web.home');
        Route::get($prefix . '/about', 'Front\AboutController@index')->name('web.about.index');
        Route::get($prefix . '/about/{categorySlug}', 'Front\AboutController@showByCategory')->name('web.about.category.show');
        Route::get($prefix . '/about/{categorySlug}/{slug}', 'Front\AboutController@show')->name('web.about.show');
        Route::get($prefix . '/santa-angela', 'Front\SantaAngelaController@index')->name('web.santa-angela.index');
        Route::get($prefix . '/santa-angela/regula', 'Front\SantaAngelaController@regula')->name('web.santa-angela.regula');
        Route::get($prefix . '/santa-angela/nasehat', 'Front\SantaAngelaController@nasehat')->name('web.santa-angela.nasehat');
        Route::get($prefix . '/santa-angela/warisan', 'Front\SantaAngelaController@warisan')->name('web.santa-angela.warisan');
        Route::get($prefix . '/news', 'Front\NewsController@index')->name('web.news.index');
        Route::get($prefix . '/news/all', 'Front\NewsController@all')->name('web.news.all');
        Route::get($prefix . '/news/{slug}', 'Front\NewsController@show')->name('web.news.show');
        Route::get($prefix . '/admission/beasiswa', 'Front\AdmissionController@beasiswa')->name('web.admission.beasiswa');
        Route::get($prefix . '/admission/faq', 'Front\AdmissionController@faq')->name('web.admission.faq');
        Route::get($prefix . '/campuses', 'Front\CampusesController@index')->name('web.campuses.index');
        Route::get($prefix . '/campuses/unit/{campusUnitId}', 'Front\CampusesController@showCampusUnit')->name('web.campus.unit.show');
        Route::get($prefix . '/school-life', 'Front\SchoolLifeController@index')->name('web.school-life.index');
        Route::get($prefix . '/school-life/{categorySlug}', 'Front\SchoolLifeController@showByCategory')->name('web.school-life.category.show');
        Route::get($prefix . '/school-life/{categorySlug}/{slug}', 'Front\SchoolLifeController@show')->name('web.school-life.show');
        //Route::get($prefix. '/school-life/pembelajaran-daring', 'Front\SchoolLifeController@pembelajaranDaring' )->name('web.school-life.pembelajaran-daring');
    });
});

$webUnitAllowed = '^(kbtk-sby|sd-sby|smp-sby|sma-sby|kbtk-sda|sd-sda|smp-sda|smp-pacet)$';
Route::group(['domain' => $routeService->getWebUnitSubdomain(), 'where' => ['webunit' => $webUnitAllowed]], function () use ($routeService, $webUnitAllowed) {
    $prefix = 'webunit/{webunit}';
    if ($routeService->isProduction()) {
        $prefix = '';
    }

    //WEB KB-TK
    Route::group(['middleware' => 'web', 'where' => ['webunit' => $webUnitAllowed], 'as' => 'webunit.'], function () use ($prefix, $webUnitAllowed) {
        Route::get($prefix . '/', 'WebUnit\HomeController@index')->name('home');
        Route::get($prefix . '/about/history', 'WebUnit\AboutController@history')->name('about.history');
        Route::get($prefix . '/about/about', 'WebUnit\AboutController@about')->name('about.about');
        Route::get($prefix . '/about/welcome', 'WebUnit\AboutController@welcome')->name('about.welcome');
        Route::get($prefix . '/about/core-values', 'WebUnit\AboutController@coreValues')->name('about.core-values');
        Route::get($prefix . '/news', 'WebUnit\NewsController@index')->name('news');
        Route::get($prefix . '/news/all', 'WebUnit\NewsController@all')->name('news.all');
        Route::get($prefix . '/news/show/{slug}', 'WebUnit\NewsController@show')->name('news.show');
        Route::get($prefix . '/facilities', 'WebUnit\FacilitiesController@index')->name('facilities');
    });
});

Route::group(['domain' => $routeService->getKantinSubdomain()], function () use ($routeService) {
    $prefix = 'kantin';
    if ($routeService->isProduction()) {
        $prefix = '';
    }

    Route::group(['middleware' => 'web'], function () use ($prefix) {
        Route::get($prefix . '/', 'WebKantin\HomeController@index')->name('kantin.index');
        Route::get($prefix . '/login', 'WebKantin\LoginController@landing')->name('kantin.login');
        Route::post($prefix . '/login', 'WebKantin\LoginController@login')->name('kantin.submit');
        Route::get($prefix . '/fetch-product-detail/{product?}', 'WebKantin\HomeController@fetchProductDetail')->name('kantin.fetch-product-detail');
        Route::get($prefix . '/search', 'WebKantin\HomeController@search')->name('kantin.search');
        Route::get($prefix . '/search/not-found', 'WebKantin\HomeController@notFound')->name('kantin.search.notFound');
    });
    Route::group(['middleware' => ['web']], function () use ($prefix) {
        Route::get($prefix . '/cart/{type?}', 'WebKantin\CartController@index')->name('kantin.cart.index');
        Route::get($prefix . '/', 'WebKantin\HomeController@index')->name('kantin.index');
        Route::post($prefix . '/cart/add', 'WebKantin\CartController@add')->name('kantin.cart.add');
        Route::post($prefix . '/cart/delete/', 'WebKantin\CartController@delete')->name('kantin.cart.delete');
        Route::post($prefix . '/cart/checkout', 'WebKantin\CartController@checkout')->name('kantin.cart.checkout');

        Route::get($prefix . '/history', 'WebKantin\OrderHistoryController@index')->name('kantin.history');
        Route::get($prefix . '/history/detail/{id?}', 'WebKantin\OrderHistoryController@show')->name('kantin.history.order.detail');
        Route::post($prefix . '/history/upload/file', 'WebKantin\OrderHistoryController@upload_file')->name('kantin.history.upload.file');
        Route::get($prefix . '/order/{id}/pdf', 'WebKantin\OrderHistoryController@showPdf')->name('kantin.history.order.pdf');
        Route::get($prefix . '/logout', 'WebKantin\LoginController@logout')->name('kantin.logout');
    });
});

Route::group(['domain' => $routeService->getPaymentsSubdomain(), 'middleware' => ['cors']], function () use ($routeService, $helper) {
    $prefix = 'payment/';
    // if ($routeService->isProduction()) {
    //     $prefix = '';
    // }

    if ($helper->isApiVaBcaEnable()) {
        // TEMP
        Route::post($prefix . 'api/auth/token', 'Payment\PaymentBCAController@authToken')->name('payment.api.token');
        Route::post($prefix . 'va/status', 'Payment\PaymentBCAController@inquiryStatus')->name('payment.api.status');
        Route::post($prefix . 'va/bills', 'Payment\PaymentBCAController@inquiryList')->name('payment.api.inquiry');
        Route::post($prefix . 'va/payments', 'Payment\PaymentBCAController@paymentFlag')->name('payment.api.payments');

        Route::post($prefix . 'v1.0/access-token/generate-signature', 'Payment\OpenApi\v1\PaymentBCAController@getSignature')->name('payment.api.token');
        Route::post($prefix . 'v1.0/access-token/b2b', 'Payment\OpenApi\v1\PaymentBCAController@authTokenNew')->name('payment.api.token');
        Route::post($prefix . 'v1.0/transfer-va/inquiry', 'Payment\OpenApi\v1\PaymentBCAController@inquiryList')->name('payment.api.inquiry');
        Route::post($prefix . 'v1.0/transfer-va/payment', 'Payment\OpenApi\v1\PaymentBCAController@paymentFlag')->name('payment.api.payments');
        Route::post($prefix . 'v1.0/transfer-va/status', 'Payment\OpenApi\v1\PaymentBCAController@inquiryStatus')->name('payment.api.status');

        Route::post($prefix . 'v1.0/access-token/get-signature-token', 'Payment\OpenApi\v1\PaymentBCAController@getSignatureToken')->name('payment.api.token');
        Route::post($prefix . 'v1.0/access-token/verif-signature-token', 'Payment\OpenApi\v1\PaymentBCAController@verifSignatureToken')->name('payment.api.token');
        Route::post($prefix . 'v1.0/access-token/get-inquiry-status', 'Payment\OpenApi\v1\PaymentBCAController@getInquiryStatus')->name('payment.api.token');

        Route::post($prefix . 'v1.0/transfer-va/inquiry-test', 'Payment\OpenApi\v1\PaymentBCATestController@inquiryList')->name('payment.api.inquiry-test');
        Route::post($prefix . 'v1.0/transfer-va/payment-test', 'Payment\OpenApi\v1\PaymentBCATestController@paymentFlag')->name('payment.api.payments-test');
    }
});
