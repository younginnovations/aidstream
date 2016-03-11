<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
@include('includes.header')
<section class="main-container">
    <div class="organisation-list-wrapper">
        <div class="col-md-12 text-center">
            <h1>550 organisations are using AidStream</h1>

            <p>The organisations listed below are using AidStream to publish their aid data in IATI.</p>

            <div class="organisations-list width-900">
                <ul>
                    <li><span>Aids Fonds - STOP AIDS NOW! - Soa Aids Nederland</span></li>
                    <li><img src="images/org/ic-adra.png" alt=""></li>
                    <li><img src="images/org/ic-awc.png" alt=""></li>
                    <li><img src="images/org/ic-amref-flying.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><span>Agence Française de Développement</span></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><span>Aids Fonds - STOP AIDS NOW! - Soa Aids Nederland</span></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                    <li><img src="images/org/ic-add.png" alt=""></li>
                </ul>
            </div>
            <a href="#" class="load-more">Load More</a>
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
                $('.navbar-toggle').click(function(){
                    $('.navbar-collapse').toggleClass('in');
                    $(this).toggleClass('collapsed');
                });
            }
        }
        hamburgerMenu();

        function inRemove() {
            if ($(window).width() > 601) {
                $('.navbar-collapse').removeClass('in');
            }
        }
        inRemove();

//        $(window).resize(function () {
//            function hamburgerMenu() {
//                if ($(window).width() < 600) {
//                    console.log("test");
//                    //responsive menu
//                    $('.navbar-toggle').click(function(){
//                        $('.navbar-collapse').toggleClass('in');
//                        $(this).toggleClass('collapsed');
//                    });
//                }
//            }
//            hamburgerMenu();
//        });
    });
</script>
</body>
</html>
