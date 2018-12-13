<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
	{{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
	{{ header("Pragma: no-cache") }}
	{{ header("Expires: 0 ")}}
	<title>AidStream Nepal - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/vendor.min.css') }}">
    <link href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" sizes="32*32" href="{{asset('/images/np/favicon-np.png') }}"/>
     <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/style.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/np/css/np.min.css')}}" />
    @yield('links')
</head>
<body>

    @yield('content')

    @include('np.partials.footer')

    <script src="{{ asset('/np/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/np/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            function hamburgerMenu() {
                $('.navbar-toggle.collapsed').click(function () {
                    $('.navbar-collapse').toggleClass('out');
                    $(this).toggleClass('collapsed');
                });
            }

            hamburgerMenu();
        });
    </script>

    @yield('script')

</body>
</html>