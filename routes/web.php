<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', static function () {
    return view('welcome');
});

/**
 * Route for generate Yandex Kassa URI and redirect to it (now redirection commented)
 */
Route::get('/invoice/pay/{id}', [
    'middleware' => 'get.invoice',
    'uses' => 'Api\InvoiceController@pay',
    'name' => 'get.invoice',
]);

/**
 * Route for Yandex Kassa webhook
 */
Route::any('/invoice/check', 'Api\InvoiceController@check');
