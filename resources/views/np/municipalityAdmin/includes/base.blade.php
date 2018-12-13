<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <title>AidStream - @yield('title')</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" />
    <link href="{{ asset('/css/vendor.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/np/css/np.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/np/css/lite.min.css') }}" rel="stylesheet">
    <link rel='shortcut icon' type='image/png' sizes='32*32' href='/images/np/favicon-np.png' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.1.0/introjs.min.css" rel="stylesheet" />

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body class="municipality-admin">
  @include('np.municipalityAdmin.includes.navbar')
    <div class="container main-container">
        <div class="row">
            @include('np.municipalityAdmin.includes.sidebar')
            @yield('content')
        </div>
    </div>

    @if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/modernizr.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.datetimepicker.full.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/script.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/datatable.js')}}"></script>
    @else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
    @endif

    <!--D3-->
    <script src="https://d3js.org/d3.v4.min.js"></script>
    @yield('script')
    @yield('foot')

</body>

</html>