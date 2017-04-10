<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    {{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
    {{ header("Pragma: no-cache") }}
    {{ header("Expires: 0 ")}}
    <title>@lang('title.aidstream')</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="css/vendor.min.css">
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>
@include('includes.header')
<section class="main-container">
    <div class="introduction-wrapper bottom-line">
        <div class="col-md-12 text-center">
            {{--<h1>Publish your Aid data in <a href="http://iatistandard.org/">IATI format</a> effortlessly</h1>--}}

            <h1>@lang('home.effortlessly_publish_your_aid_data',['route' => 'http://iatistandard.org/'])</h1>

            {{--<p>AidStream is an online platform for organisations that wish to publish aid data in the International Aid--}}
            {{--Transparency Initiative(IATI) format without getting into complexities of IATI. </p>--}}

            <p>
                @lang('home.aidstream_is_online_platform')
            </p>

            @if(auth()->user())
                <a href="{{ route('registration') }}" class="btn btn-primary get-started-btn">@lang('global.get_started')</a>
            @else
                <a href="#convinceWrapper" class="btn btn-primary get-started-btn">@lang('global.get_started')</a>
            @endif

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
            <p>
                <a href="{{ url('/who-is-using') }}">{{ $organizationCount }}</a> @lang('home.organisations_are_using_aidstream')
            </p>
        </div>
    </div>
    <div class="information-wrapper bottom-line">
        <div class="information-section">
            <div class="col-md-12 width-900">
                <div class="left-wrapper">
                    <h2>@lang('home.less_iati_xml_complexities')</h2>

                    <p>@lang('home.entering_data_in_aidstream')</p>
                </div>
                <div class="right-wrapper">
                    <img src="images/img-1.png" alt="">
                </div>
            </div>
        </div>
        <div class="information-section">
            <div class="col-md-12 width-900">
                <div class="left-wrapper">
                    <h2>@lang('home.easy_to_use_interface')</h2>

                    <p>
                        @lang('home.aidstream_as_a_clear')
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
                    <h2>@lang('home.publish_data_easily')</h2>

                    <p>
                        @lang('home.aidstream_uses_the_form')
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
    <div class="convince-wrapper" id="convinceWrapper">
        <div class="col-md-12 text-center width-900">
            <h2>@lang('home.still_not_convinced')</h2>
            <p>@lang('home.did_we_mention_that_free')</p>
            {{--<a href="{{ url('/register') }}" class="btn btn-primary get-started-btn">@lang('global.get_started')</a>--}}
        </div>
    </div>
    @if(!auth()->user())
    <div class="version-wrapper" id="versionWrapper">
        <div class="col-xs-12 width-900">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="panel-heading text-center">
                    <img src="{{ url('/images/ic-lite-logo.svg') }}" alt="Lite" width="40" height="37">
                    <h3>Lite</h3>
                </div>
                <div class="panel-body text-left">
                    <ul>
                        <li>Basic IATI fields to get you started</li>
                        <li>For small organizations that don’t understand IATI</li>
                    </ul>
                    <p>You will be able to upgrade to Core any time you want.</p>
                    <a href="{{ route('registration', config('system-version.Lite.id')) }}" class="btn btn-line">Start now</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="panel-heading text-center">
                    <span>We recommend!</span>
                    <img src="{{ url('/images/ic-core-logo.svg') }}" alt="Core" width="40" height="37">
                    <h3>Core</h3>
                </div>
                <div class="panel-body text-left">
                    <ul>
                        <li>With both basic and other fields of IATI</li>
                        <li>For large (and small) organizations that have a good understanding of IATI</li>
                    </ul>
                    <a href="{{ route('registration', config('system-version.Core.id')) }}" class="btn">Start now</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="panel-heading text-center">
                    <img src="{{ url('/images/ic-custom-logo.svg') }}" alt="Custom" width="52" height="45">
                    <h3>Custom</h3>
                </div>
                <div class="panel-body text-left">
                    <ul>
                        <li>Customized to meet your organizations’ needs</li>
                        <li>For organizations that have multiple organizations under them.</li>
                    </ul>
                    <a href="mailto:support@aidstream.org" class="btn btn-line">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

@if(session()->has('secondary_contact_name'))
    <div class="modal fade recovery-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body clearfix text-center ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <div class="col-md-12 text-center verification-wrapper">
                        <img src={{ url('/images/ic-sent-mail.svg') }} alt="mail" width="88" height="94">
                        <h1>@lang('global.thank_you')!</h1>
                        <p>
                            @lang('home.an_email_containing_your_account')
                        </p>
                        <p>
                            @lang('home.if_you_need_any_help',['route' => route('contact', ['has-secondary-contact-support'])])
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('message'))
    <div class="modal fade message-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header ">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 text-center verification-wrapper">
                        <img src={{ url('/images/ic-sent-mail.svg') }} alt="mail" width="88" height="94">
                        <h1>@lang('global.thank_you')!</h1>
                        <p>{!! session('message') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('verification_message'))
    <div class="modal fade verification-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-verify-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('registration.email_verification')</h4>
                </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 text-center verification-wrapper">
                        <img src={{ url('/images/ic-sent-mail.svg') }} alt="mail" width="88" height="94">
                        <h1>@lang('global.thank_you')!</h1>
                        <p>{!! session('verification_message') !!}</p>
                    </div>
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

        if ($('.modal').length > 0) {
            $('.modal').modal('show')
        }

        $('#languages').change(function () {
            window.location.href = '/switch-language/' + $(this).val();
        });

        $(document).on('click', '.get-started-btn', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 1000);
            event.preventDefault();
        });
    });
</script>
</body>
</html>
