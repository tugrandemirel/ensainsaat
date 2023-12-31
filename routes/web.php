<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\RealEstateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CKEditorController;

use App\Http\Controllers\Front\FIndexController;
use App\Http\Controllers\Front\FRealEstateController;
use App\Http\Controllers\Front\FServiceController;
use App\Http\Controllers\Front\FNewsLetterController;
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
Route::middleware('ViewShare')->group(function () {
    Route::get('/', [FIndexController::class, 'index'])->name('home');
    Route::as('realestate.')->group(function (){
        Route::get('/emlak', [FRealEstateController::class, 'index'])->name('index');
        Route::get('/emlak/{purpose}', [FRealEstateController::class, 'purposeHome'])->name('purporse');
        Route::get('/emlak/{purpose}/{slug}', [FRealEstateController::class, 'show'])->name('show');
    });

    Route::as('service.')->group(function (){
        Route::get('/hizmetlerimiz',[FServiceController::class, 'index'])->name('index');
        Route::get('/hizmetlerimiz/{slug}',[FServiceController::class, 'show'])->name('show');
    });

    Route::as('newsletter.')->group(function (){
        Route::get('/basinda-biz',[FNewsLetterController::class, 'index'])->name('index');
        Route::get('/basinda-biz/{slug}',[FNewsLetterController::class, 'show'])->name('show');
    });
});


Auth::routes();

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin/')->as('admin.')->middleware('auth')->group(function (){
    Route::get('/', function () {
        return view('admin.index');
    })->name('home');

    Route::resource('hesap-ayarlari', AccountController::class)
            ->only('index', 'update')
            ->parameters(['hesap-ayarlari' => 'user'])
            ->names('account');
    Route::resource('hizmetlerimiz', ServiceController::class)
            ->except('show')
            ->parameters(['hizmetlerimiz' => 'service'])
            ->names('service');

    Route::resource('basinda-biz', NewsLetterController::class)
            ->only('index', 'create', 'store', 'edit', 'update', 'destroy')
            ->parameters(['basinda-biz' => 'newsletter'])
            ->names('newsletter');

    Route::resource('emlak', RealEstateController::class)
            ->only('index', 'create', 'store', 'edit', 'update', 'destroy')
            ->parameters(['emlak' => 'realestate'])
            ->names('realestate');


    Route::resource('site-ayarlari', SettingController::class)
            ->only('index', 'store','update')
            ->parameters(['site-ayarlari' => 'setting'])
            ->names('setting');

    Route::post('ckeditor/image_upload', [CKEditorController::class, 'upload'])->name('upload');

});
