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

Route::get('/home', 'HomeController@index')->name('home');


Route::get('asistencia','AsistenciaController@index')->name('asistencia.index');
Route::post('asistencia/store','AsistenciaController@store')->name('asistencia.store');
Route::get('asistencia/edit/{id}','AsistenciaController@edit')->name('asistencia.edit');
Route::delete('asistencia/destroy/{id}','AsistenciaController@destroy')->name('asistencia.destroy');

Route::get('template',function(){

    return view('layouts.template');
});