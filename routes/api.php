<?php

use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Api Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::prefix("slider")->group(function () {
    Route::post("", "SliderController@store");
    Route::post("update", "SliderController@update");
    Route::delete("{id}", "SliderController@delete");
    Route::get("", "SliderController@index");
    Route::get("all", "SliderController@getAllSliders");
});

Route::prefix("services")->group(function () {
    Route::post("", "ServiceController@store");
    Route::post("update", "ServiceController@update");
    Route::delete("{id}", "ServiceController@delete");
    Route::get("", "ServiceController@index");
    Route::get("latest/{limit}", "ServiceController@getLatestServices");
});

Route::prefix("auth")->group(function () {
    Route::post("register", "AuthController@register");
    Route::post("login", "AuthController@login");
    Route::get("verify-token", "AuthController@verifyToken");
    Route::get("verify-admin", "AuthController@verifyAdmin");
    Route::get("logout", "AuthController@logout");
    Route::get('forget-password/{user:email}', "AuthController@forgetPassword");
    Route::post('reset-password', "AuthController@resetPassword");
});

Route::prefix("about")->group(function () {
    Route::post("", "AboutController@save");
    Route::get("first", "AboutController@getFirst");
});

Route::prefix("clients")->group(function () {
    Route::post("", "ClientController@store");
    Route::post("update", "ClientController@update");
    Route::delete("{id}", "ClientController@delete");
    Route::get("", "ClientController@index");
    Route::get("latest/{limit}", "ClientController@getLatestClients");
});

Route::prefix("counters")->group(function () {
    Route::post("", "CounterController@store");
    Route::post("update", "CounterController@update");
    Route::delete("{id}", "CounterController@delete");
    Route::get("", "CounterController@index");
    Route::get("", "CounterController@index");
    Route::get("latest/{limit}", "CounterController@getLatestCounters");
});

Route::prefix("employees")->group(function () {
    Route::post("", "EmployeeController@store");
    Route::post("update", "EmployeeController@update");
    Route::delete("{id}", "EmployeeController@delete");
    Route::get("", "EmployeeController@index");
    Route::get("latest/{limit}", "EmployeeController@getLatestEmployees");
});

Route::prefix("reviews")->group(function () {
    Route::post("", "ReviewController@store");
    Route::post("update", "ReviewController@update");
    Route::delete("{id}", "ReviewController@delete");
    Route::get("", "ReviewController@index");
    Route::get("all", "ReviewController@getAllReviews");
});

Route::prefix("partner-logos")->group(function () {
    Route::post("", "PartnerLogoController@store");
    Route::post("update", "PartnerLogoController@update");
    Route::delete("{id}", "PartnerLogoController@delete");
    Route::get("", "PartnerLogoController@index");
    Route::get("all", "PartnerLogoController@getAllLogos");
});

Route::prefix("articles")->group(function () {
    Route::post("", "ArticleController@store");
    Route::post("update", "ArticleController@update");
    Route::delete("{id}", "ArticleController@delete");
    Route::get("", "ArticleController@index");
    Route::get("{article}", "ArticleController@show");
    Route::get("latest/{limit}", "ArticleController@getLatestArticles");
});

Route::prefix("contact-us")->group(function () {
    Route::post("", "ContactUsController@save");
    Route::get("first", "ContactUsController@getFirst");
});

Route::prefix("galleries")->group(function () {
    Route::post("", "GalleryController@store");
    Route::post("update", "GalleryController@update");
    Route::delete("{id}", "GalleryController@delete");
    Route::get("", "GalleryController@index");
});

Route::prefix("faqs")->group(function () {
    Route::post("", "FaqController@store");
    Route::post("update", "FaqController@update");
    Route::delete("{id}", "FaqController@delete");
    Route::get("", "FaqController@index");
});

Route::prefix("products")->group(function () {
    Route::post("", "ProductController@store");
    Route::post("update", "ProductController@update");
    Route::delete("{id}", "ProductController@delete");
    Route::get("", "ProductController@index");
    Route::get("all", "ProductController@getAllProducts");
    Route::get("{product}", "ProductController@show");
});

Route::prefix("contact-form")->group(function () {
    Route::post("", "ContactFormController@store");
    Route::get("", "ContactFormController@index");
});

Route::prefix("cart")->group(function () {
    Route::post("", "CartController@addToCart");
    Route::delete("{productId}", "CartController@removeCartItem");
    Route::get("", "CartController@getCartItems");
});

Route::prefix("payment")->group(function () {
    Route::get("cash", "PaymentController@cashPayment");
    Route::get("online", "PaymentController@onlinePayment");
    Route::get("callback-success", "PaymentController@callback_success")->name("callback_success");
    Route::get("callback-error", "PaymentController@callback_error")->name("callback_error");
});

Route::prefix("orders")->group(function () {
    Route::get("", "OrderController@getAllOrders");
    Route::get("user", "OrderController@getUserOrders");
    Route::put("", "OrderController@updateOrderStatus");
});
