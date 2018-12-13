<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>@lang('title.aidstream_register')</title>
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
    <link href="{{ asset('/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>

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
<div class="login-wrapper">
    <div class="container-fluid register-container">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    </div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </span>
                            </div>
                        @endif
                        <h1 class="text-center">@lang('global.find_your_organisation')</h1>
                        <p class="text-center">
                            @lang('global.seems_there_are_similar_organisations')
                        </p>
                        <div class="similar-org-container">
                            {{ Form::open(['url' => route('submit-similar-organization'), 'method' => 'post', 'id' => 'similar-org-form']) }}

                            <div class="input-wrapper">
                                <div class="col-xs-12 col-md-12 {{ $orgName ? 'hidden' : '' }}">
                                    {{ Form::hidden('type', $type) }}
                                    {!! AsForm::text(['name' => 'search_org', 'class' => 'search_org ignore_change', 'value' => $orgName, 'label' => false]) !!}
                                    {{ Form::button(trans('search_organisation'), ['class' => 'btn btn-primary btn-search', 'type' => 'button']) }}
                                    {{ Form::hidden('similar_organization') }}
                                </div>
                                <div class="org-list-container clickable-org hidden">
                                    <div class="col-xs-12 col-md-12 organization-list-wrapper">
                                        <p class="text-center">@lang('global.search_organisation_text')</p>
                                        <ul class="organization-list">
                                        </ul>
                                    </div>
                                    <div class="col-md-12 text-center org-list-notification">
                                        <p>@lang('global.none_of_the_results_match_organisation_text', ['url' => url('/register')])</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 text-center clickable-org org-list-notification">
                                {{--<a data-value="" class="btn btn-continue">Continue with registration</a>--}}
                                {{ Form::button(trans('global.continue'), ['class' => 'btn btn-primary btn-submit btn-register prevent-disable hidden', 'type' => 'submit', 'disabled' => 'disabled']) }}
                            </div>
                            {{ Form::close() }}
                        </div>
                        <div class="similar-org-action text-center hidden">
                            <h2>"<span class="org-name"></span>"</h2>
                            <div class="col-md-12 identifier-information">
                                <p>@lang('organisation.if_this_is_your_organisation')</p>
                                <div class="col-sm-6">
                                    <h3>@lang('global.administrator_information')</h3>
                                    <p>
                                        @lang('global.administrator_of_organisation')
                                    </p>
                                    <span class="admin-name"></span>
                                    <a href="{{ route('contact', ['contact-admin-for-same-org']) }}" class="btn btn-primary">@lang('global.contact_administrator')</a>
                                </div>
                                <div class="col-sm-6">
                                    <h3>@lang('global.retrieve_login_credentials')</h3>
                                    <p>
                                        @lang('global.forgotten_login_credentials_text')
                                    </p>
                                    <a href="{{ route('contact', ['contact-support-for-same-org']) }}" class="btn btn-primary">@lang('global.contact_support_for')</a>
                                </div>
                            </div>
                            <button class="btn btn-back">@lang('global.back_to_organisation_list')</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 create-account-wrapper">
                <a href="{{ url('/auth/login') }}">@lang('global.already_have_account')</a>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')

<script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{url('/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery-ui-1.10.4.tooltip.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/registration.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('form select').select2();
        Registration.filterSimilarOrg();
    });
</script>
<!-- Google Analytics -->
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
</body>
</html>
