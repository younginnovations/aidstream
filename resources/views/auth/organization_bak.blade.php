<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream - Register</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form select').select2();
        });
    </script>

    @yield('head')
</head>
<body>
<header>
    <nav class="navbar navbar-default navbar-static">
        <div class="navbar-header">
            <a href="{{ url('/') }}" class="navbar-brand">Aidstream</a>
            <button type="button" class="navbar-toggle collapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </button>
        </div>
        <div class="navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a class="{{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                <li><a class="{{ Request::is('who-is-using') ? 'active' : '' }}" href="{{ url('/who-is-using') }}">Who's Using It?</a></li>
                <li><a href="https://github.com/younginnovations/aidstream-new/wiki/User-Guide" target="_blank">User Guide</a></li>
                <!--<li><a href="#">Snapshot</a></li>-->
            </ul>
            <div class="action-btn pull-left">
                @if(auth()->check())
                    <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}" class="btn btn-primary">Go
                        to Dashboard</a>
                @else
                    <a href="{{ url('/auth/login')}}" class="btn btn-primary">Login/Register</a>
                @endif
            </div>
        </div>
    </nav>
</header>

<div class="login-wrapper">
    {{--    <div class="language-select-wrapper">
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
    <div class="container-fluid register-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img src="{{url('images/logo.svg')}}" alt="">

                        <div class="panel-title">Register</div>
                    </div>
                    <div class="panel-body">
                        @include('includes.response')

                        {{--*/ $orgInfo = (array) (old() ? old() : session('org_info')); /*--}}

                        {{ Form::model($orgInfo, ['url' => route('registration.save-organization'), 'method' => 'post', 'id' => 'organization-form']) }}

                        <div class="input-wrapper">
                            <p>Please provide the information below about the organisation you want to create an account for on AidStream.</p>
                        </div>

                        <div class="input-wrapper">
                            <div class="col-xs-12 col-md-12">
                                {!! AsForm::text(['name' => 'organization_name', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
                                {!! AsForm::text(['name' => 'organization_name_abbr', 'label' => 'Organization Name Abbreviation', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}
                            </div>
                            <div class="col-xs-12 col-md-12">
                                {!! AsForm::select(['name' => 'organization_type', 'data' => $orgType, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select a Type']) !!}
                            </div>
                            <div class="col-xs-12 col-md-12">
                                {!! AsForm::text(['name' => 'organization_address', 'label' => 'Address', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
                                {!! AsForm::select(['name' => 'country', 'data' => $countries, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select a Country']) !!}
                            </div>
                            <div class="col-xs-12 col-md-12{{ $errors->get('organization_identifier') ? ' has-error' : '' }}">
                                {!! AsForm::select(['name' => 'organization_registration_agency', 'data' => $orgRegAgency, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select an Agency']) !!}
                                {!! AsForm::text(['name' => 'registration_number', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
                                <p>
                                    <button type="button" class="btn-xs btn-link add_agency">Add Agency</button>
                                    {{ Form::hidden('agencies', ($agencies = getVal($orgInfo, ['agencies'], [])) ? $agencies : json_encode($orgRegAgency), ['class' => 'form-control', 'id' => 'agencies', 'data-agency' => getVal($orgInfo, ['organization_registration_agency'])]) }}
                                    {{ Form::hidden('new_agencies', null, ['class' => 'form-control', 'id' => 'new_agencies']) }}
                                    {{ Form::hidden('agency_name', null, ['class' => 'form-control', 'id' => 'agency_name']) }}
                                    {{ Form::hidden('agency_website', null, ['class' => 'form-control', 'id' => 'agency_website']) }}
                                </p>
                            </div>
                            <div class="text-center">
                                IATI Organizational Identifier: <span id="org_identifier">[Registration Agency]-[Registration Number]</span>
                                {{ Form::hidden('organization_identifier', null, ['class' => 'form-control', 'id' => 'organization_identifier']) }}

                                @foreach($errors->get('organization_identifier') as $message)
                                    <div class="text-danger">{{ $message }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            {{ Form::button('Continue Registration', ['class' => 'btn btn-primary btn-submit btn-register', 'type' => 'submit', 'disabled' => 'disabled']) }}
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 create-account-wrapper">
                <a href="{{ url('/auth/login') }}">I already have an account</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="reg_agency" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Agency</h4>
            </div>
            {{ Form::open(['url' => route('agency.store'), 'method' => 'post', 'id' => 'reg-agency-form']) }}
            <div class="modal-body clearfix">

                <div class="messages hidden"></div>
                <div class="form-container hidden">
                    {{--*/
                    $messages = $errors->get('name');
                    /*--}}
                    <div class="form-group {{ $messages ? ' has-error' : '' }}">
                        {{ Form::label('name', null, ['class' => 'control-label required col-xs-12 col-sm-4']) }}
                        <div class="col-xs-12 col-sm-8">
                            {{ Form::text('name') }}
                            @foreach($messages as $message)
                                <div class="text-danger">{{ $message }}</div>
                            @endforeach
                        </div>
                    </div>
                    {{--*/
                    $messages = $errors->get('short_form');
                    /*--}}
                    <div class="form-group {{ $messages ? ' has-error' : '' }}">
                        {{ Form::label('short_form', null, ['class' => 'control-label required col-xs-12 col-sm-4']) }}
                        <div class="col-xs-12 col-sm-8">
                            {{ Form::text('short_form') }}
                            @foreach($messages as $message)
                                <div class="text-danger">{{ $message }}</div>
                            @endforeach
                        </div>
                    </div>
                    {{--*/
                    $messages = $errors->get('website');
                    /*--}}
                    <div class="form-group {{ $messages ? ' has-error' : '' }}">
                        {{ Form::label('website', null, ['class' => 'control-label required col-xs-12 col-sm-4']) }}
                        <div class="col-xs-12 col-sm-8">
                            {{ Form::url('website') }}
                            <p class="help-block">eg: http://www.example.com</p>
                            @foreach($messages as $message)
                                <div class="text-danger">{{ $message }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@include('includes.footer')

@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="{{url('/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/registration.js')}}"></script>
<script type="text/javascript">
    var agencies = JSON.parse($('#agencies').val());
    $(document).ready(function () {
        Registration.abbrGenerator();
        Registration.checkAbbrAvailability();
        Registration.changeCountry();
        Registration.regNumber();
        Registration.disableOrgSubmitButton();
        Registration.addRegAgency();
    });
</script>
</body>
</html>
