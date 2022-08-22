<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title }} | @if(!empty($appName->application_name)) {{ $appName->application_name }} @endif</title>
    @include('dashboard.layouts.head')
</head>

<body>
    <div class="container-scroller">
        @yield('content')
    </div>
    <!-- container-scroller -->
    @include('dashboard.layouts.scripts')
    @yield('scripts')
</body>

</html>
