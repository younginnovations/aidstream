<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>@lang('title.aidstream_forgot_password')</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>
<body>
@include('includes.header_home')
<div class="register-wrapper">
    {{--		    <div class="language-select-wrapper">
                    <label for="" class="pull-left">Language</label>
                <div class="language-selector pull-left">
                    <span class="flag-wrapper"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ config('app.locale') }}"></span></span>
                    <span class="caret pull-right"></span>
                </div>
                        <ul class="language-select-wrap language-flag-wrap">
                            @foreach(config('app.locales') as $key => $val)
                            <li class="flag-wrapper" data-lang="{{ $key }}"><span class="img-thumbnail flag flag-icon-background flag-icon-{{ $key }}"></span><span class="language">{{ $val }}</span></li>
                            @endforeach
                        </ul>
                </div>--}}
    <div class="container-fluid login-container contact-admin-container">
        <div class="row">
            <h1 class="text-center">{{ $contactTitle }}</h1>
            {{--*/
                $template = request()->route()->template;
            /*--}}
            @if($template == 'need-new-user' || $template == 'forgot-user-email' || $template == 'contact-admin-for-same-org')
                <div class="text-center contact-info-container">
                    <p>@lang('global.you_have_confirmed_name_organisation')</p>
                    <h2>"<span class="org-name">{{ session('org_name') }}</span>"</h2>
                    <p>@lang('global.admin_for_organisation') <span>{{ session('admin_name') }}</span></p>
                    <p>@lang('global.fill_out_form_below_will_send_message')</p>
                </div>
            @endif

            @if($template == 'no-secondary-contact-support')
                <div class="text-center contact-info-container">
                    <p>@lang('global.recover_administrator_account')</p>
                </div>
            @endif
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-danger">{{ session('error_message') }}</div>
                        {{ Form::open(['method' => 'post', 'id' => 'form-contact']) }}
                        <div class="login-form-group">
                            {!! AsForm::text(['name' => 'full_name', 'label' => trans('registration.your_full_name'),'required' => true]) !!}
                            {!! AsForm::email(['name' => 'email', 'label' => trans('registration.your_email_address'), 'required' => true]) !!}
                            {!! AsForm::textarea(['name' => 'message', 'label' => trans('registration.your_message'),'required' => true]) !!}
                        </div>
                        {{ Form::button('Submit', ['class' => 'btn btn-primary btn-submit btn-form-default', 'type' => 'submit']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
<!-- Scripts -->
@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="{{url('/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/additional-methods.js')}}"></script>
<script type="text/javascript" src="{{url('/js/contact.js')}}"></script>
<script>
    $(document).ready(function () {
        Contact.load();
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
