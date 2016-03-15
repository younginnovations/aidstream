<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
@include('includes.header')
<section class="main-container">
    <div class="introduction-wrapper about-wrapper">
        <div class="col-md-12 text-center">
            <h1>About us</h1>

            <p>We made AidStream to help organisations easily publish their data in IATI and make it available in open
                format. AidStream stands as one such platform, that presents complexity of the IATI in an understandable
                and a consumable way.</p>
        </div>
        <div class="screenshot text-center">
            <img src="images/screenshot.png" alt="">
        </div>
    </div>
    <div class="col-md-12 text-center heading-title">
        <h2>Who is behind AidStream?</h2>
    </div>
    <div class="information-wrapper bottom-line">
        {{--<div class="information-section about-information-section">--}}
            {{--<div class="col-md-12 width-900 text-center">--}}
                {{--<div class="left-wrapper">--}}
                    {{--<img src="images/ic-aidinfo.png" alt="">--}}
                {{--</div>--}}
                {{--<div class="right-wrapper">--}}
                    {{--<p>Aidinfo is based in the UK and works to accelerate poverty reduction by making aid more--}}
                        {{--transparent, particularly through its support of the International Aid Transparency Initiative.--}}
                        {{--It acts as the secretariat and provides logistical support for the IATI Technical Advisory--}}
                        {{--Group.</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="information-section about-information-section">
            <div class="col-md-12 width-900 text-center">
                <div class="left-wrapper">
                    <img src="images/ic-yipl.png" alt="">
                </div>
                <div class="right-wrapper">
                    <p>
                        <a href="http://younginnovations.com.np/" target="_blank">YoungInnovations</a> is a Nepal-based company providing cutting-edge software and web solutions to a
                        wide range of partners in Nepal and abroad. Currently it is implementing the "Mobile Social
                        Networking Nepal" initiative in partnership with the World Bank's infoDev programme to build a
                        community around mobile technologies. YoungInnovations is also becoming increasingly involved in
                        the open data arena.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.footer')
<link rel="stylesheet" href="css/style.css">
<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        function hamburgerMenu() {
            if ($(window).width() < 600) {
                //responsive menu
                $('.navbar-toggle.collapsed').click(function(){
                    $('.navbar-collapse').toggleClass('out');
                    $(this).toggleClass('collapsed');
                });
            }
        }
        hamburgerMenu();
    });
</script>
</body>
</html>
