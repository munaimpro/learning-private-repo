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
    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}"></link> {{-- Toastify JS Link --}}
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form enctype="multipart/form-data">
                            <input type="file" id="file">
                            <button class="btn btn-primary" id="uploadButton" onclick="uploadExcelData(event)">Upload</button>
                        </form>
                        <h1 class="upload-message d-none">Uploading excel</h1>
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
    function showLoader () {
        document.querySelector('.upload-message').classList.remove('d-none');
    }

    function hideLoader() {
        document.querySelector('.upload-message').classList.add('d-none');
    }


    const uploadButton = document.querySelector('#uploadButton');

    async function uploadExcelData(event) {
        event.preventDefault();
        
        try {
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];

            // Check if a file is selected
            if (!file) {
                alert('Please select a file to upload.');
                return;
            }

            // formData object creation for sending Ajax request
            const formData = new FormData();
            formData.append('import_file', file);

            showLoader();
            const response = await axios.post('upload-excel-data', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            hideLoader();

            console.log(response.data);
        } catch (error) {
            console.error('Error uploading excel:', error);
        }
    }

</script>