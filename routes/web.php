<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackboneController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('interface-status', [BackboneController::class, 'interfaceStatus'])->name('interface-status');
Route::get('monitor-traffic/{int}', [BackboneController::class, 'monitorTraffic'])
    ->name('monitor-traffic');
Route::get('client-list/{dhcpServer}', [BackboneController::class, 'dhcpClient'])->name('client-list');

// Route::get('/monitor-traffic/{interface}', 'BackboneController@monitorTraffic')->name('monitor-traffic');
