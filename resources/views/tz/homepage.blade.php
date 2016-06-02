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
    {{--<link rel="stylesheet" href="css/main.min.css">--}}
    <link href="{{ asset('/css/tanzania_style/tz.style.css') }}" rel="stylesheet">
</head>
<body class="front-page">
<div class="header-banner">
    @include('tz.partials.header')
    <div class="introduction-wrapper bottom-line">
        <div class="col-md-12 text-center">
            <h1>Publish your Aid data in <a href="http://iatistandard.org/">IATI format</a> format effortlessly </h1>

            <p>
                For organisations based in Tanzania
            </p>

            <a href="{{ url('/auth/register') }}" class="btn btn-primary get-started-btn">Get Started</a>
        </div>
    </div>
</div>
<section class="main-container container">

    <div class="col-md-12">
        <div class="search-wrap">
            <input type="text" placeholder="Search for an activity...">
        </div>
    </div>

    <div class="col-md-12">
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
                <tr>
                    <td colspan="5">
                        <div class="text-center no-data"> You haven’t added any Projects yet.
                            <a href="#" class="btn btn-primary">Add a Project</a>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>


  {{--  <div class="organization-wrapper bottom-line">
        <div class="col-md-12 width-900">
            <ul>
                <li><img src="images/ic-org-add.png" alt=""></li>
                <li><img src="images/ic-org-adra.png" alt=""></li>
                <li><img src="images/ic-org-awc.png" alt=""></li>
                <li><img src="images/ic-org-amref-flying.png" alt=""></li>
                <li><img src="images/ic-org-amref-health.png" alt=""></li>
                <li><img src="images/ic-org-apt.png" alt=""></li>
            </ul>
            --}}{{--<p>387 organisations have published their aid data. <a href="{{ url('/who-are-using') }}">{{ $organizationCount }}</a> have done it through AidStream</p>--}}{{--
            <p><a href="{{ url('/who-is-using') }}">{{ $organizationCount }}</a> organisations are using AidStream.</p>
        </div>
    </div>
    <div class="information-wrapper bottom-line">
        <div class="information-section">
            <div class="col-md-12 width-900">
                <div class="left-wrapper">
                    <h2>Less IATI XML complexities</h2>

                    <p>Entering data in AidStream is as easy as filling out a simple form. Unsure what XML is, or how to
                        create it? No problem! AidStream hides all the complexities and technicalities of the final XML
                        file so that you can focus on inputting clear data in the right place.</p>
                </div>
                <div class="right-wrapper">
                    <img src="images/img-1.png" alt="">
                </div>
            </div>
        </div>
        <div class="information-section">
            <div class="col-md-12 width-900">
                <div class="left-wrapper">
                    <h2>Easy-to-use interface</h2>
                    <p>
                        AidStream has a clear, clean and easy-to-use interface which allows you to quickly add and edit
                        projects, as well as offering you the option of importing projects in bulk. Using AidStream
                        guarantees that your data will always be logged correctly in the right section, with no messy
                        XML causing you to make mistakes!
                    </p>
                </div>
                <div class="right-wrapper">
                    <img src="images/img-2.png" alt="">
                </div>
            </div>
        </div>
        <div class="information-section">
            <div class="col-md-12 width-900">
                <div class="left-wrapper">
                    <h2>Publish data easily!</h2>

                    <p>
                        AidStream uses the form you fill out to generate the necessary XML files and sends your data
                        direct to the IATI Registry - all with a single click! All you have to do is sit back and relax
                        - AidStream takes care of everything else.
                    </p>
                </div>
                <div class="right-wrapper">
                    <img src="images/img-3.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="convince-wrapper">
        <div class="col-md-12 text-center width-900">
            <h2>Still not convinced?</h2>

            <p>Did we mention that it’s free!? You can’t go wrong - with AidStream, publishing your data to IATI is a
                piece of cake!</p>
            <a href="{{ url('/auth/register') }}" class="btn btn-primary get-started-btn">Get Started</a>
        </div>
    </div>--}}
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
