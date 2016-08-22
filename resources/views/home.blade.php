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
</head>
<body>
@include('includes.header')
<section class="main-container">
    <div class="introduction-wrapper bottom-line">
        <div class="col-md-12 text-center">
            {{--<h1>Publish your Aid data in <a href="http://iatistandard.org/">IATI format</a> effortlessly</h1>--}}

            <h1>Effortlessly publish your Aid data in <a href="http://iatistandard.org/">IATI format</a></h1>

            {{--<p>AidStream is an online platform for organisations that wish to publish aid data in the International Aid--}}
            {{--Transparency Initiative(IATI) format without getting into complexities of IATI. </p>--}}

            <p>
                AidStream is an online platform for organisations that wish to publish aid data in accordance with the
                International Aid Transparency Initiative(IATI) format but want to avoid dealing with the complexities
                of creating XML.
            </p>

            <a href="{{ url('/register') }}" class="btn btn-primary get-started-btn">Get Started</a>

            <div class="screenshot">
                <img src="images/screenshot.png" alt="">
            </div>
        </div>
    </div>
    <div class="organization-wrapper bottom-line">
        <div class="col-md-12 width-900">
            <ul>
                <li><img src="images/ic-org-add.png" alt=""></li>
                <li><img src="images/ic-org-adra.png" alt=""></li>
                <li><img src="images/ic-org-awc.png" alt=""></li>
                <li><img src="images/ic-org-amref-flying.png" alt=""></li>
                <li><img src="images/ic-org-amref-health.png" alt=""></li>
                <li><img src="images/ic-org-apt.png" alt=""></li>
            </ul>
            {{--<p>387 organisations have published their aid data. <a href="{{ url('/who-are-using') }}">{{ $organizationCount }}</a> have done it through AidStream</p>--}}
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
                        activities, as well as offering you the option of importing activities in bulk. Using AidStream
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
    {{--<div class="testimonials-wrapper">--}}
    {{--<div class="col-md-12 text-center">--}}
    {{--<blockquote>--}}
    {{--<p>"Neat - AidStream makes it easy for an organisation to publish #IATI #opendata"</p>--}}

    {{--<div class="detail">--}}
    {{--<h3>Tariq Khokhar</h3>--}}
    {{--<span>Global Data Editor, WorldBank</span>--}}
    {{--<img src="images/speaker.png" alt="">--}}
    {{--</div>--}}
    {{--</blockquote>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="convince-wrapper">
        <div class="col-md-12 text-center width-900">
            <h2>Still not convinced?</h2>

            <p>Did we mention that it’s free!? You can’t go wrong - with AidStream, publishing your data to IATI is a
                piece of cake!</p>
            <a href="{{ url('/register') }}" class="btn btn-primary get-started-btn">Get Started</a>
        </div>
    </div>
</section>

@if(session()->has('secondary_contact_name'))
    <div class="modal fade recovery-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body clearfix text-center ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p>
                        Your account password reset email has been sent to your organisation's secondary contact holder.
                    </p>
                    @if($secContactName = session('secondary_contact_name'))
                        <p>Secondary Contact Name: {{ $secContactName }}</p>
                    @endif
                    <a href="{{ route('contact', ['has-secondary-contact-support']) }}" class="btn btn-primary">Click here to contact AidStream support</a>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('message'))
    <div class="modal fade message-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body clearfix">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {!! session('message') !!}
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('verification_message'))
    <div class="modal fade verification-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Email Verification</h4>
                </div>
                <div class="modal-body clearfix">
                    {!! session('verification_message') !!}
                </div>
            </div>
        </div>
    </div>
@endif

@include('includes.footer')
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

        var verificationModel = $('.verification-modal');
        $('.close', verificationModel).click(function () {
            verificationModel.next('.modal-backdrop').remove();
            verificationModel.remove();
        });

        if ($('.message-modal').length > 0) {
            $('.message-modal').modal('show')
        }

        if ($('.verification-modal').length > 0) {
            $('.verification-modal').modal('show')
        }

        if ($('.recovery-modal').length > 0) {
            $('.recovery-modal').modal('show')
        }
    });
</script>
</body>
</html>
