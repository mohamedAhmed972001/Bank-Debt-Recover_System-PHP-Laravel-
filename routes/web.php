<?php

use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicePaidController;
use App\Http\Controllers\InvoicePartiallyController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\InvoiceUnpaidController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\invoice_paid;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified','check_status'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('invoices', InvoicesController::class);
Route::resource('invoices-archive', InvoicesArchiveController::class);
Route::resource('sections', SectionsController::class);
Route::resource('products', ProductsController::class);
Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class); // ازاى راح للستور ؟؟؟
/*إذا كنت تقوم بإرسال نموذج (form) إلى /InvoiceAttachments باستخدام method="POST"، سيتم استدعاء دالة store. */
Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);
Route::get('/Invoices_Details/{id}', [InvoicesDetailsController::class, 'edit']);
Route::get('/Invoices_Details/{id}', [InvoicesDetailsController::class, 'edit']);
Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class,'open_file']);
Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class,'get_file']);
Route::get('edit_invoice/{invoice_id}', [InvoicesController::class,'edit']);
Route::get('Status_show/{invoice_id}', [InvoicesController::class,'show'])->name('Status_show');
Route::get('invoice_print/{invoice_id}', [InvoicesController::class,'invoice_print'])->name('Invoice_print');
Route::get('invoices-paid', [InvoicesController::class,'invoices_paid']);
Route::get('invoices-unpaid', [InvoicesController::class,'invoices_unpaid']);
Route::get('invoices-partially', [InvoicesController::class,'invoices_partially']);

Route::post('Status_Update/{invoice_id}', [InvoicesController::class,'Status_Update'])->name('Status_Update');
Route::post('archive.update/{invoice_id}', [InvoicesArchiveController::class,'update'])->name('archive.update');
Route::post('delete_file', [InvoicesDetailsController::class,'destroy'])->name('delete_file');

Route::get('export_invoices', [InvoicesController::class, 'export']);

Route::get('invoices_report', [InvoicesReportController::class, 'index']);
Route::post('Search_invoices', [InvoicesReportController::class, 'Search_invoices']);

Route::get('customers_report', [CustomersReportController::class, 'index']);
Route::post('Search_customers', [CustomersReportController::class, 'Search_customers']);

Route::get('MarkAsRead_All', [InvoicesController::class, 'MarkAsRead_All'])->name('MarkAsRead_All');


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});
    
require __DIR__.'/auth.php';
