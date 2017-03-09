<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    {{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
    {{ header("Pragma: no-cache") }}
    {{ header("Expires: 0 ")}}
    <title>Aidstream Lite</title>
    <link rel="shortcut icon" type="/image/png" sizes="16*16" href="/images/favicon.png"/>
    <link rel="stylesheet" href="/css/vendor.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
</head>
<body>
@include('lite.partials.header')

@yield('content')
@include('lite.partials.footer')
<script src="/js/jquery.js"></script>
<script src="/js/modernizr.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script>

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
</body>
</html>

