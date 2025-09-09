<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('excel_upload_page');
});

Route::post('upload-excel-data', [CustomerController::class, 'uploadExcelData']);
