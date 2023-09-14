<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use App\Models\Employee;

use App\Events\SendGlobalNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('page.dashboard');
});

Route::get('/test', [OrderController::class, 'cobaPush'])->name('cobaPush');

Route::group(['middleware' => 'auth'], function () {
    //Menuju Antrian Controller (Admin)
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/design', [DesignController::class, 'index'])->name('design.index');
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
});

Route::group(['middleware' => 'checkrole:admin'], function () {
    //Menuju Design Controller (Admin)
    Route::get('/antrian/{id}/edit', [AntrianController::class, 'edit'])->name('antrian.edit');
    Route::put('/antrian/{id}', [AntrianController::class, 'update'])->name('antrian.update');
    Route::delete('/antrian/{id}', [AntrianController::class, 'destroy'])->name('antrian.destroy');
});

//membuat route group untuk AuthController
Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('auth.index');
    Route::get('/register', 'create')->name('auth.register');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/register', 'store')->name('auth.store');
    Route::get('/logout', 'logout')->name('auth.logout');
    Route::get('/beams-generateToken', 'generateToken')->name('beams.auth');
});

Route::controller(ReportController::class)->group(function(){
    Route::get('/report-workshop', 'pilihTanggal')->name('laporan.workshop');
    Route::post('/report-workshop-pdf', 'exportLaporanWorkshopPDF')->name('laporan-workshop-pdf');
});

Route::controller(DesignController::class)->group(function(){
    Route::post('/design/simpan-file-produksi', 'simpanFileProduksi')->name('simpanFileProduksi');
    Route::get('/design/download-file-produksi/{id}', 'downloadFileProduksi')->name('downloadFileProduksi');
});

Route::controller(EmployeeController::class)->group(function(){
    Route::get('/profile/{id}', 'show')->middleware('auth')->name('employee.show');
    Route::put('/profile/{id}', 'update')->middleware(['auth'])->name('employee.update');
    Route::post('/profile/upload-foto', 'uploadFoto')->middleware(['auth'])->name('employee.uploadFoto');
});

Route::controller(OrderController::class)->group(function(){
    Route::get('/order/create', 'create')->name('order.create');
    Route::post('/order', 'store')->name('order.store');
    Route::get('/order/{id}/edit', 'edit')->name('order.edit');
    Route::put('/order/{id}', 'update')->name('order.update');
    Route::delete('/order/{id}', 'destroy')->name('order.destroy');
    Route::get('/design', 'antrianDesain')->name('design.index');
    Route::get('/order/{id}/take', 'ambilDesain')->name('order.take');
    Route::post('/order/upload-print-file', 'uploadPrintFile')->name('design.upload');
    Route::get('/design/submit-file-cetak/{id}', 'submitFileCetak')->name('submit.file-cetak');
    Route::get('/order/{id}/toAntrian', 'toAntrian')->middleware(['auth', 'checkrole:sales'])->name('order.toAntrian');
    Route::post('/order/tambahProdukByModal', 'tambahProdukByModal')->name('tambahProdukByModal');
    Route::get('/get-jobs-by-category/{category_id}', 'getJobsByCategory')->name('getJobsByCategory');
});

Route::controller(AntrianController::class)->group(function(){
    Route::post('/antrian/storeToAntrian', 'store')->middleware('auth')->name('antrian.store');
    Route::post('/antrian/{id}/updateDeadline', 'updateDeadline')->middleware('auth')->name('antrian.updateDeadline');
    Route::get('/antrian/dokumentasi/{id}', 'showDokumentasi')->middleware('auth')->name('antrian.showDokumentasi');
    Route::post('/antrian/storeDokumentasi', 'storeDokumentasi')->middleware('auth')->name('antrian.storeDokumentasi');
    Route::get('/design/download/{id}', 'downloadPrintFile')->name('design.download');
    Route::get('/antrian/submitDokumentasi/{id}', 'submitDokumentasi')->middleware('auth')->name('antrian.submitDokumentasi');
    Route::get('/list-machines', 'getMachine')->name('antrian.getMachine');
    Route::get('/estimator/index', 'estimatorIndex')->middleware('auth')->name('estimator.index');
    Route::get('/antrian/showProgress/{id}', 'showProgress')->middleware('auth')->name('antrian.showProgress');
    Route::post('/antrian/storeProgress', 'storeProgressProduksi')->middleware('auth')->name('store.progressProduksi');
    Route::get('/antrian/mark-aman/{id}', 'markAman')->middleware('auth')->name('antrian.markAman');
    Route::get('/antrian/download-produksi-file/{id}', 'downloadProduksiFile')->middleware('auth')->name('antrian.downloadProduksi');
});

Route::controller(ProductController::class)->group(function(){
    Route::get('/product', 'index')->name('product.index');
    Route::get('/product/create', 'create')->name('product.create');
    Route::post('/product', 'store')->name('product.store');
    Route::get('/product/{id}/edit', 'edit')->name('product.edit');
    Route::put('/product/{id}', 'update')->name('product.update');
    Route::delete('/product/{id}', 'destroy')->name('product.destroy');
});

Route::controller(CustomerController::class)->group(function(){
    Route::get('/customer', 'index')->name('customer.index');
    Route::get('/customer/create', 'create')->name('customer.create');
    Route::post('/customer', 'store')->name('customer.store');
    Route::get('/customer/search', 'search')->name('pelanggan.search');
    Route::get('/customer/searchByNama', 'searchById')->name('pelanggan.searchById');
    Route::post('/customer/store', 'store')->name('pelanggan.store');
});

Route::controller(JobController::class)->group(function(){
    Route::get('/job/search', 'search')->name('job.search');
    Route::get('/job/searchByNama', 'searchByNama')->name('job.searchByNama');
});

Route::controller(DocumentationController::class)->group(function(){
    Route::get('/documentation/{id}', 'previewDokumentasi')->name('documentation.preview');
});

Route::controller(UserController::class)->group(function(){
    Route::get('/user/superadmin', 'index')->middleware(['auth', 'checkrole:superadmin'])->name('user.index');
    Route::get('/user/create', 'create')->middleware(['auth', 'checkrole:superadmin'])->name('user.create');
    Route::get('/user/{id}/edit', 'edit')->middleware(['auth', 'checkrole:superadmin'])->name('user.edit');
    Route::put('/user/update/{id}', 'update')->middleware(['auth', 'checkrole:superadmin'])->name('user.update');
    Route::delete('/user/{id}', 'destroy')->middleware(['auth', 'checkrole:superadmin'])->name('user.destroy');
});

Route::get('/error', function () {
    //menampilkan halaman error dan error message
    if (session('error')) {
        $error = session('error');
        return view('error', compact('error'));
    }
})->name('error.page');
