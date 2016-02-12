@extends('app')

@section('title', 'Change Username - ' . Auth::user()->first_name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="panel-content-heading panel-title-heading">Current Username</div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div><span>Current Username : </span>{{Auth::user()->username}}</div>
                        <div><span>Email : </span>{{Auth::user()->email}}</div>
                    </div>
                </div>
                <div class="panel-content-heading panel-title-heading">Change Username</div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('user.update-username', $user->id)}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label">Organization User Identifier</label>
                            <input type="text" class="form-control" name="organization_user_identifier" value="{{ old('organization_user_identifier') }}">
                            <div><span>Your organisation user identifier will be used as a prefix for all the AidStream users in your organisation. We recommend that you use a short abbreviation that uniquely identifies your organisation. If your organisation is 'Acme Bellus Foundation', your organisation user identifier should be 'abf', depending upon it's availability.</span></div>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}" readonly="readonly">
                            <div><span>This will be your new username with Organisation User Identifier as a prefix. You will not be able to change '_admin' part of the username.</span></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-form btn-submit">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

