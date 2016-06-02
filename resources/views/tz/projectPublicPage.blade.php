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
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
    <link href="{{ asset('/css/tanzania_style/tz.style.css') }}" rel="stylesheet">
</head>
<body>
@include('tz.partials.header')

<section class="main-container">
    <div class="container">
        <div class="title-section">
            <h1>Add International</h1>
            <div class="location"><span>Vallis House 57, Vallis Way FROME Somerset BA11 3EG</span></div>
            <div class="social-logo">
                <span><a href="mailto:mike.evans@add.org.uk" class="mail">mike.evans@add.org.uk</a></span>
                <span><a href="http://add.org.uk" class="web">http://add.org.uk</a></span>
                <span><a href="#" class="twitter"></a></span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="header-name-value name-value-section clearfix">
                    <dl class="col-md-3">
                        <dt>Total Commitments</dt>
                        <dd class="amount">$999999999</dd>
                    </dl>
                    <dl class="col-md-3">
                        <dt> Total Disbursements</dt>
                        <dd class="amount">$999999999</dd>
                    </dl>
                    <dl class="col-md-3">
                        <dt>Total Expenditures</dt>
                        <dd class="amount">$999999999</dd>
                    </dl>
                    <dl class="col-md-3">
                        <dt> Total Incoming Funds</dt>
                        <dd class="amount">$999999999</dd>
                    </dl>

                </div>

            </div> {{--  row close--}}
        </div>
    </div> {{--  container close--}}


    <div class="container container--shadow">
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
