<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    @include('client.layouts.partials.css')
    <title>@yield('document')</title>
</head>

<body>
    <!-- Start Header/Navigation -->
    @include('client.layouts.partials.nav')
    <!-- End Header/Navigation -->

    @yield('content')

    <!-- Start Footer Section -->
    @include('client.layouts.partials.footer')

    <!-- End Footer Section -->


    @include('client.layouts.partials.js')

</body>

</html>
