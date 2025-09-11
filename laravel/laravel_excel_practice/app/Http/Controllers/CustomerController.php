<?php

namespace App\Http\Controllers;

use PDF;
use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\CustomerImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    // Function to upload customer data from excel
    public function uploadExcelData(Request $request)
    {
        // Validation process
        $request->validate([
            'import_file' => [
                'required',
                'file',
            ],
        ]);

        try {
            // Excel import process
            Excel::import(new CustomerImport, $request->file('import_file'));

            // Return response
            return response()->json(['status' => 'success', 'successMessage' => 'File imported successfully!']);
        } catch (Exception $error) {
            return response()->json([
                'status'       => 'fail',
                'errorMessage' => 'File does not imported!',
                'debugMessag'  => $error->getMessage(),
            ]);
        }
    }

    // Function to get customer data from database
    public function getExcelData()
    {
        // Getting customer data
        $customers = Customer::get(['name', 'email', 'phone', 'country']);

        return response()->json([
            'status'        => 'success',
            'customer-data' => $customers,
        ]);
    }

    // Function to generate pdf
    public function generatePdf(): JsonResponse {
        try {
            // Get data to be used in the PDF
            $customers = Customer::get(['name', 'email', 'phone', 'country']);

            // Correct way to use the PDF facade to load a view
            $pdf = PDF::loadView('pdf_report_page', compact('customers'));

            // Define a unique file name to avoid overwriting
            $fileName = 'customer-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';

            // Define the path where the PDF will be saved
            $filePath = storage_path('app/public/pdfs/' . $fileName);

            // Check if the directory exists, and create it if it doesn't
            $directory = dirname($filePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save the PDF file to the specified path
            $pdf->save($filePath);

            // Get the public URL for the saved file
            $fileUrl = asset('storage/app/public/pdfs/' . $fileName);

            // Return a success response with the public URL
            return response()->json([
                'status' => 'success',
                'message' => 'PDF report successfully generated.',
                'pdf_url' => $fileUrl,
            ]);

        } catch (Exception $error) {
            // Log the error for debugging
            Log::error('PDF Generation Error: ' . $error->getMessage());

            // Return a failure response
            return response()->json([
                'status' => 'fail',
                'message' => 'Failed to generate PDF report.',
                'debugMessage' => $error->getMessage(),
            ], 500); // Internal Server Error
        }
    }
}
