<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    // Function to upload customer data from excel
    function uploadExcelData (Request $request) {
        // Validation process
        $request->validate([
            'import_file' => [
                'required',
                'file'
            ],
        ]);

        try {
            // Excel import process
            Excel::import(new CustomerImport, $request->file('import_file'));

            // Return response
            return response()->json(['status' => 'success', 'successMessage' => 'File imported successfully!']);

        } catch (Exception $error) {
            return response()->json([
                'status' => 'fail',
                'errorMessage' => 'File does not imported!',
                'debugMessag' => $error->getMessage()
            ]); 
        }
    }
}
