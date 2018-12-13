@extends('np.main')
@section('title', 'Register')

@section('links')
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
@include('np.partials.header')
<div class="login-wrapper">
    <div class="container-fluid login-container reset-container">
        <div class="row">
            <h1 class="text-center">@lang('global.reset_password')</h1>
            <p class="text-center">@lang('global.reset_password_text')</p>
            <div class="col-lg-4 col-md-8 reset-block">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

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

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/forgot/password') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="input-wrapper reset-input-wrapper">
                                <div class="form-group">
                                    <label class="control-label required">@lang('global.your') @lang('user.email_address')</label>

                                    <div class="col-md-12">
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                    </div>
                                </div>
                                </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-submit">
                                    @lang('global.send_password_reset_link')
                                </button>
                            </div>
                            <div class="organisation-account-wrapper">
                                <p class="text-center">
                                   @lang('registration.email_address_forgotten_text')
                                </p>
                                <p>
                                    @if(session('same_identifier_org_id'))
                                        <a href="{{ route('submit-similar-organization', 'user') }}" class="btn btn-primary btn-submit">@lang('global.user_account')</a>
                                        <a href="{{ route('submit-similar-organization', 'admin') }}" class="btn btn-primary btn-submit">@lang('global.administrator_account')</a>
                                    @else
                                        <a class="btn btn-primary btn-submit btn-type" data-type="user">@lang('global.user_account')</a>
                                        <a class="btn btn-primary btn-submit btn-type" data-type="admin">@lang('global.administrator_account')</a>
                                    @endif
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Similar Organisations Modal -->
<div class="modal fade preventClose" id="similar-org-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg register-container" role="document">
        <div class="modal-content form-body">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body same-identifier org-warning">
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
                        @lang('global.recover_account_organisation_name')
                    </p>
                    <div class="similar-org-container">
                        {{ Form::open(['url' => route('submit-similar-organization'), 'method' => 'post', 'id' => 'similar-org-form']) }}

                        <div class="input-wrapper">
                            <div class="col-xs-12 col-md-12">
                                {{ Form::hidden('type') }}
                                {!! AsForm::text(['name' => 'search_org', 'class' => 'search_org ignore_change', 'label' => false]) !!}
                                {{ Form::button(trans('global.search_organisation'), ['class' => 'btn btn-primary btn-search', 'type' => 'button']) }}
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
                            <div class="org-list-container no-org-list hidden">
                                <div class="col-xs-12 col-md-12 organization-list-wrapper">
                                    <p class="text-center">
                                        @lang('global.database_doesnt_contain_organisation_text')
                                        <br/>
                                        @lang('global.would_you_like_to_register_text')
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center clickable-org org-list-notification">
                            {{ Form::button('Continue', ['class' => 'btn btn-primary btn-submit btn-register prevent-disable hidden', 'type' => 'submit', 'disabled' => 'disabled']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@if(env('APP_ENV') == 'local')
    <script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/jquery.cookie.js')}}"></script>
@else
    <script type="text/javascript" src="{{url('/js/main.min.js')}}"></script>
@endif
<script type="text/javascript" src="{{url('/js/ga.js')}}"></script>
<!-- End Google Analytics -->
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/registration.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        Registration.filterSimilarOrg();
    });
</script>
@endsection
