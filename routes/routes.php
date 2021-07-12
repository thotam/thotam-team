<?php

use Illuminate\Support\Facades\Route;
use Thotam\ThotamTeam\Http\Controllers\TeamController;

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

Route::middleware(['web', 'auth', 'CheckAccount', 'CheckHr', 'CheckInfo'])->group(function () {

    //Route Admin
    Route::redirect('admin', '/', 301);
    Route::group(['prefix' => 'admin'], function () {

        //Route quản lý nhóm
        Route::redirect('member', '/', 301);
        Route::group(['prefix' => 'member'], function () {

            Route::get('team',  [TeamController::class, 'index'])->name('admin.member.team');

            Route::post('quanly_select',  [TeamController::class, 'quanly_select'])->name('admin.member.quanly_select');
        });

    });

});
