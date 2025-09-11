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
        .card-header {
            padding: 2rem;
            background: #000000;
            color: #ffffff;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        table {
            width: 100%
        }

        thead {
            background: #dddddd
        }

        tr {
            border-bottom: 1px solid #eeeeee
        }

        th, td {
            padding: 1rem
        }

    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Customer List</h1>
                    </div>
                    <div class="card-body">
                        <table>
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
                                @php $i = 1; @endphp
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $customer['name'] }}</td>
                                        <td>{{ $customer['email'] }}</td>
                                        <td>{{ $customer['phone'] }}</td>
                                        <td>{{ $customer['country'] }}</td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
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