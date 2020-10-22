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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/settings', 'SettingsController@index')->name('settings');

Route::get('/guildwar-statistic', 'GuildWarController@index')->name('guildwar-statistic')->middleware('auth');
Route::get('/guildwar-statistic/{param1?}', 'GuildWarController@index');
Route::get('/guildwar-statistic/{param1?}/{param2}', 'GuildWarController@index');

Route::post('/export-guild', 'ExportController@store')->name('export');
Route::post('/export-guildwar', 'GuildWarController@store')->name('export-guildwar');
Route::post('/export-guildwar-match', 'GuildWarController@storeMatch')->name('export-guildwar');
Route::post('/export-guildsiege-match', 'ExportController@storeGuildSiegeMatch')->name('export-guildsiege-match');