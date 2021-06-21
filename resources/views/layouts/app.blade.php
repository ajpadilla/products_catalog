<html>
<head>
    <title>App Name - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>


<div class="container">
    @yield('content')
</div>

@section('script')
@show

</body>
</html>
