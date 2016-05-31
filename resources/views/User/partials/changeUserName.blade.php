@extends('app')

@section('title', 'Change Username - ' . Auth::user()->first_name)

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.errors')
                <div class="element-panel-heading">
                    <div>Change Username</div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper panel-profile profile-content-wrapper">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('user.update-username', $user->id)}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group col-xs-12 col-sm-6 col-md-6">
                            <div class="col-xs-12 col-md-12">
                                <label class="control-label">Organization User Identifier</label>
                                <input type="text" class="form-control noSpace" name="organization_user_identifier" value="{{ old('organization_user_identifier') }}">
                                <div><span>Your organisation user identifier will be used as a prefix for all the AidStream users in your organisation. We recommend that you use a short abbreviation that uniquely identifies your organisation. If your organisation is 'Acme Bellus Foundation', your organisation user identifier should be 'abf', depending upon it's availability.</span></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 username_text">
                            <label class="control-label">Username</label>
                            <em>This will be auto-generated as you fill Organization User Identifier.</em>
                        </div>
                        <div class="col-xs-12 col-sm-6 username_value hidden">
                            <label class="control-label">Username</label>
                            <input type="hidden" class="form-control hover_help_text" name="username" value="{{ old('username') }}" readonly="readonly">
                            <div class="alternate_input">{{ old('username') }}</div>
                        <span class="help-text"
                              title="AidStream will create a default username with your Organisation User Identifier as prefix. You will not be able to change '_admin' part of the username. This user will have administrative privilege and can create multiple AidStream users with different set of permissions."
                              data-toggle="tooltip" data-placement="top">
                           AidStream will create a default username with your Organisation User Identifier as prefix. You will not be able to change '_admin' part of the username. This user will have administrative privilege and can create multiple AidStream users with different set of permissions.
                        </span>
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

