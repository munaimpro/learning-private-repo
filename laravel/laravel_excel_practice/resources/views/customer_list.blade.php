<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Excel Upload</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> {{-- Bootstrap CSS --}}
    <script src="{{ asset('assets/js/axios.min.js') }}"></script> {{-- Axios JS Link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}">
    </link> {{-- Toastify JS Link --}}
    <style>
        @media print {
    /* Hide the button with the 'no-print' class */
    .no-print,
    .btn {
        display: none !important;
    }

    .card-header {
        background: #000000;
        color: #ffffff;
    }

    /* Set body and container styles for printing */
    body {
        margin: 0;
        padding: 0;
    }
    .container {
        width: 100%;
    }

    /* Ensure the card and table styles are preserved */
    .card,
    .table {
        box-shadow: none !important;
        /* border: 1px solid #dee2e6; */
    }
    .table th,
    .table td {
        border-color: #dee2e6;
    }

    @page {
        size: A4 portrait;
        margin: 2cm;
    }

    html {
        print-color-adjust: exact;
    }


}
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card" id="customerContent">
                    <h1 class="upload-message d-none">Generating report</h1>
                    <div class="card-header">
                        <h1>Customer List</h1>
                        <button onclick="printReport()" class="btn btn-primary">
                        Print  
                        </button> 
                    </div>
                    <div class="card-body">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <p class="upload-message d-none">Fetching Customer List</p>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Bootstrap JS Link --}}
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    {{-- Feather Icons Link --}}
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    {{-- jQuery Link --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    {{-- Toastify JS Link --}}
    <script src="{{ asset('assets/js/toastify.js') }}"></script>
    {{-- Custom JS File: Main.js Link --}}
    <script src="{{ asset('assets/js/main.js') }}?v={{ rand() }}"></script>
</body>

</html>




<script>
    function showLoader() {
        document.querySelector('.upload-message').classList.remove('d-none');
    }

    function hideLoader() {
        document.querySelector('.upload-message').classList.add('d-none');
    }

    async function getExcelData() {
        try {
            const tBody = document.querySelector('#tbody');
            tBody.innerHTML = ''; // Empty tbody

            showLoader();

            // Corrected: Removed unnecessary headers from GET request
            const response = await axios.get('get-excel-data');

            hideLoader();

            if (response && response.data && response.data['customer-data']) {
                let i = 1;
                response.data['customer-data'].forEach((customer_data) => {
                    const tableContent = `<tr>
                        <td>${i}</td>
                        <td>${customer_data.name}</td>
                        <td>${customer_data.email}</td>
                        <td>${customer_data.phone}</td>
                        <td>${customer_data.country}</td>
                    </tr>`;
                    i++;
                    tBody.insertAdjacentHTML('beforeend', tableContent);
                });
            }
        } catch (error) {
            hideLoader();
            console.error('Error fetching data:', error);
            // You can also display a user-friendly toast message
        }
    }

    getExcelData();


    async function generateReport() {
        try {
            showLoader();

            // Make an Axios GET request to the generate-pdf route
            const response = await axios.get('generate-pdf');

            if (response.data.status === 'success' && response.data.pdf_url) {
                // Open the generated PDF in a new tab using the returned URL
                window.open(response.data.pdf_url, '_blank');
                Toastify({
                    text: response.data.message,
                    duration: 3000,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#4CAF50',
                }).showToast();
            } else {
                // Handle a successful but unexpected response
                Toastify({
                    text: 'An error occurred. PDF URL not found.',
                    duration: 3000,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#FF5733',
                }).showToast();
            }

        } catch (error) {
            console.error('Error generating PDF:', error);
            const errorMessage = error.response?.data?.message || 'Failed to generate report.';
            Toastify({
                text: errorMessage,
                duration: 3000,
                gravity: 'top',
                position: 'center',
                backgroundColor: '#FF5733',
            }).showToast();

        } finally {
            hideLoader();
        }
    }


    function printReport() {
        // const printableContent = document.getElementById('customerContent').innerHTML;
        // const originalContent = document.body.innerHTML;

        // Hide the body content and replace it with the printable area
        // document.body.innerHTML = printableContent;

        // Trigger the print dialog
        window.print();

        // Restore the original content after printing
        document.body.innerHTML = originalContent;

        // You may need to reload or re-initialize scripts if your page is complex
        // For a simple page like this, it should work fine.
    }

</script>