<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('excel_upload_page');
});

Route::post('upload-excel-data', [CustomerController::class, 'uploadExcelData']);
Route::view('customer-list', 'customer_list');
Route::get('get-excel-data', [CustomerController::class,'getExcelData']);
Route::get('generate-pdf', [CustomerController::class, 'generatePdf']);
