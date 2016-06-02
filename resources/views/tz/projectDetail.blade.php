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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.min.css">
    <link href="{{ asset('/css/tanzania_style/tz.style.css') }}" rel="stylesheet">
</head>
<body>
    @include('tz.partials.header')

<section class="main-container">
    <div class="container">
        <div class="title-section">
            <h1>Unite for Body Rights Ethiopia</h1>
            <div>NL-KVK-41150298-5113</div>
            <div class="light-text">Last updated on: Oct 4, 2015</div>
        </div>
    </div>
   
    <div class="container container--shadow">
        <div class="col-md-12 intro-section clearfix">
            <div class="col-md-3 vertical-horizontal-center-wrap">
                <div class="vertical-horizontal-centerize">
                    <div class="organization-logo"><img src= {{asset("./images/ic_add-international.svg")}} width="106px" height="100px"> </div>
                    <div class="organization-name">Tanzania office</div>
                </div>
            </div>
            <div class="col-md-9" style="height: 277px;"></div>
        </div>
        <div class="col-md-12 name-value-section">
            <dl class="clearfix">
                <dt class="col-md-3">General description</dt>
                <dd class="col-md-9">I’m not really sure how old I was when I got the gift for Christmas, but I remember thinking it was a pretty impressive piece of electronic hardware. It was really cool looking (technologically speaking), and I was awfully proud to own it. It certainly made for lots of fun times.</dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3">Sector</dt>
                <dd class="col-md-9">Basic education</dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3">Location</dt>
                <dd class="col-md-9 list-wrap">
                    <div>Chamwino District Council, Dodoma Region</div>
                    <div>Tanzania Airports Authority (Government)</div>
                </dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3">Sector</dt>
                <dd class="col-md-9"><a href="#">i am a link</a></dd>
            </dl>
        </div>
        <div class="col-md-12">
            <div class="title">Disbursements</div>
            <table class="table table-striped custom-table" id="data-table">
                <thead>
                <tr>
                    <th width="40%">Project Title</th>
                    <th class="">Project Identifier</th>
                    <th class="">Last Updated</th>
                    <th class="status">Status</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                </tbody>

            </table>

            <div class="title">Disbursements</div>
            <table class="table table-striped custom-table" id="data-table">
                <thead>
                <tr>
                    <th width="40%">Project Title</th>
                    <th class="">Project Identifier</th>
                    <th class="">Last Updated</th>
                    <th class="status">Status</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                <tr>
                    <td class="bold-col">Press Conference</td>
                    <td>GPAF-IMP-062</td>
                    <td class="light-col">Oct 4, 2015</td>
                    <td>Draft</td>
                </tr>
                </tbody>

            </table>
    </div>
    </div>
</section>
@include('tz.partials.footer')
<script src="js/jquery.js"></script>
<script src="js/modernizr.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
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
