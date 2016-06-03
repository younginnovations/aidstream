<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    {{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
    {{ header("Pragma: no-cache") }}
    {{ header("Expires: 0 ")}}
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.min.css') }}">
    <link href="{{ asset('/js/tz/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tanzania_style/tz.style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
</head>

<body>
<style>
    .message {
        font-size: 24px;
        color: #aaa;
    }
</style>
@include('tz.partials.header')

<section class="main-container">
    <div class="container vertical-horizontal-center-wrap">
        <div class="vertical-horizontal-centerize">
            <p class="message">You are unauthorized.</p>
        </div>
    </div>
</section>
</body>
</html>