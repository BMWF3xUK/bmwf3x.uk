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

Route::group([
    "namespace" => "Auth",
    "middleware" => "guest",
], function() {
    // Route::get("login", "LoginController@showLoginForm")->name("login");
    // Route::post("login", "LoginController@login");

    // Route::get("register", "RegisterController@showRegistrationForm")->name("register");
    // Route::post("register", "RegisterController@register");

    // Route::get("logout", "LoginController@logout")->name("logout");
    // Route::post("logout", "LoginController@logout");

    // Route::group(["prefix" => "password"], function() {
    //     Route::post("email", "ForgotPasswordController@sendResetLinkEmail")->name("password.email");
    //     Route::get("reset", "ForgotPasswordController@showLinkRequestForm")->name("password.request");
    //     Route::post("reset", "ResetPasswordController@reset");
    //     Route::get("reset/{token}", "ResetPasswordController@showResetForm")->name("password.reset");
    // });

    Route::get("login", "FacebookController@onboard")->name("login");
    Route::get("register", "FacebookController@onboard")->name("register");

    Route::group([
        "prefix" => "onboard",
        "as" => "onboard.",
    ], function() {
        Route::get("facebook", "FacebookController@onboard")->name("facebook");
        Route::get("facebook/redirect", "FacebookController@onboardResponse")->name("facebook.response");
    });
});

Route::get("/", "HomeController@index")->name("home");
Route::get("donate", "HomeController@donate")->name("donate");

Route::group([
    "prefix" => "guides",
    "as" => "guides.",
], function() {
    Route::get("/", "GuideController@index")->name("index");
    Route::get("download/{catchall}", "GuideController@download")->name("download")->where("catchall", "(.*)");
    Route::get("{catchall}", "GuideController@view")->name("view")->where("catchall", "(.*)");
});
