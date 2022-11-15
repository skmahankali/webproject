<?php

use Illuminate\Support\Facades\Route;
use Elastic\Elasticsearch;
use Elastic\Elasticsearch\ClientBuilder;
use App\Http\Controllers\Authorscontroller;
use App\Http\Controllers\ElasticController;
use App\Http\Controllers\FileUpload;


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

Route::get('/elastics', function () {  
    $client = Elasticsearch\ClientBuilder::create()->build();
    var_dump($client);});



Route::get('', function () {
    return view('welcome');
});

Route::view('home','home')->middleware(['auth', 'verified']);
Route::view('/profile/edit','profile.edit')->middleware('auth');
Route::view('/profile/password','profile.password')->middleware('auth');
Route::post('/rp', 'App\Http\Controllers\ElasticController@rp');
Route::post('/serp', 'App\Http\Controllers\ElasticController@login_ser');


Route::get('/details/{id}', 'App\Http\Controllers\ElasticController@index');

Route::get('/download',function(){
    $file=storage_path()."";
    $headers=array(
        'Content-Type:application/pdf',     
    );
    return Response::download($file,"", $headers);
});


Route::post('/upload-file', [FileUpload::class, 'fileUpload'])->name('fileUpload');
Route::get('/upload','App\Http\Controllers\ElasticController@upload');

Route::post('/upload_data_fields','App\Http\Controllers\ElasticController@upload_data_fields');
Route::get('/viewp/{pdf_id}','App\Http\Controllers\ElasticController@pdf');







