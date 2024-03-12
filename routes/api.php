<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'api', 'middleware' => ['api_lang']], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');



        Route::post('check-email', 'EmailVerificationController@check_email');
        Route::post('resend-otp-check-email', 'EmailVerificationController@resend_otp_check_email');
        Route::post('verify-email', 'EmailVerificationController@verify_email');

        Route::post('forgot-password', 'ForgotPassword@reset_password_request');
        Route::post('verify-otp', 'ForgotPassword@otp_verification_submit');
        Route::put('reset-password', 'ForgotPassword@reset_password_submit');

        Route::any('social-login', 'SocialAuthController@social_login');
    });

    Route::group(['prefix' => 'styles'], function () {
        Route::get('styles', 'StyleController@get_styles');
        Route::get('tags','StyleController@get_styles_tags');
    });

    Route::group(['prefix' => 'payment','middleware' => 'auth:api'], function () {
        
        Route::get('packages','PaymentController@get_packages');
    });

    Route::group(['prefix' => 'orders',], function () {
        Route::post('place-order', 'OrderController@place_order');
    });

    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@configuration');
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
