<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="referrer" content="origin-when-cross-origin"> {{-- Refferer information sent with request --}}
    <title>@yield('title', 'User List') - {{ config('app.name', 'WP Laravel API') }}</title> {{-- Dynamic Title based on current page --}}
    <meta name="description" content="@yield('description', 'A WordPress and Laravel connected API')"> {{-- Website Description --}}
    <meta name="author" content="Munaim Khan"> {{-- Author --}}
    <meta name="robots" content="index, follow"> {{-- Index, Follow - Home page always --}}
    <link rel="canonical" href="{{ url()->current() }}"> {{-- Canonical URL for SEO --}}
    {{-- Open Graph (Social Sharing) Meta Tags --}}
    <meta property="og:title" content="@yield('title', 'WP Laravel API') - {{ config('app.name', 'WP Laravel API') }}">
    <meta property="og:description" content="@yield('description', 'A Wordpress and Laravel connected API')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/image/social-share-image.png') }}">
    <meta property="og:image:alt" content="">
    <meta property="og:locale" content="bn_BD">
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@yourtwitterhandle"> {{-- Replace with your Twitter handle if applicable --}}
    <meta name="twitter:creator" content="@yourtwitterhandle"> {{-- Replace with your Twitter handle if applicable --}}
    <meta name="twitter:title" content="@yield('title', 'WP Laravel API') - {{ config('app.name', 'Masternit') }}">
    <meta name="twitter:description" content="@yield('description', '')">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> {{-- Bootstrap CSS --}}
    <script src="{{ asset('assets/js/axios.min.js') }}"></script> {{-- Axios JS Link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}"></link> {{-- Toastify JS Link --}}
</head>
<body>
    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1>API User</h1>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                            <th>Name</th> 
                            <th>Email</th> 
                            <th>Image</th> 
                            <th>Phone</th> 
                            </tr>
                            <tbody id="userContainer">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

{{-- Bootstrap JS Link --}}
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

{{-- Custom Script --}}
<script>
    const userContainer = document.querySelector('#userContainer');

    // Function to fetch user list
    async function fetchUser() {
        try {
            // Receive response from backend
            const response = await axios.get('users/get');
            console.log(response.data.data);

            // Check response and load data
            if (response.status === 200 && response.data.status == 'success') {
                
                // Empty existing table data
                userContainer.innerHTML = '';

                const users = response.data.data;
                users.forEach (user => {
                    const userRow = `
                    <tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td><img width="80px" height="80px" src="{{ url('/') }}/storage/${user.image}" alt="profile"></td>
                        <td>${user.phone}</td>
                    </tr>
                    `;
                    userContainer.insertAdjacentHTML('beforeend', userRow);
                });
            } else {
                userContainer.insertAdjacentHTML('beforeend', '<p class="text-center">No user available</p>')
            }
        } catch (error) {
            console.error("Error fetching user list:", error);
            
            if (error.response && error.response.data && error.response.data.message) {
                console.error('API Error: ' + error.response.data.message);
            } else {
                console.error('An network error occurred. Please check your internet connection.');
            }
        }
    }

    // Call the function on page load
    document.addEventListener('DOMContentLoaded', fetchUser);
</script>
</body>
</html>

