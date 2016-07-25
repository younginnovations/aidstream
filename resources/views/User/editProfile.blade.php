@extends('app')

@section('title', 'Edit Profile - ' . $user['first_name'])

@section('content')
    @inject('getCodeList', 'App\Core\Form\BaseForm')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.errors')
                <div class="element-panel-heading">
                    <div>Edit Profile</div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper profile-wrapper">
                    <div class="create-form">
                        {{ Form::model($user,['route' => ['user.update-profile',$user['id']], 'method'=>'POST', 'files'=>true]) }}
                        {{--*/
                            if(old()) {
                                $user['first_name']               = old('first_name');
                                $user['last_name']                = old('last_name');
                                $user['email']                    = old('email');
                                $user['time_zone_id']             = old('time_zone');
                                $organization->name             = old('organization_name');
                                $organization->address          = old('organization_address');
                                $organization->country          = old('country');
                                $organization->organization_url = old('organization_url');
                                $organization->telephone        = old('organization_telephone');
                                $organization->twitter          = old('organization_twitter');
                                $organization->disqus_comments  = old('disqus_comments');
                            }
                        /*--}}
                        <div class="input-wrapper">
                            <div class="col-md-12 col-xs-12">
                                <span class="hidden" id="user-identifier" data-id="{{ $organization->user_identifier }}"></span>
                                @if(Auth::user()->isAdmin())
                                    {!! AsForm::username(['name' => 'username', 'hiddenName' => 'login_username', 'addOnValue' => $organization->user_identifier, 'value' => substr($user['username'],strlen($organization->user_identifier)+1), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'class' => 'username' ,'attr' => ['readonly' => 'readonly']]) !!}
                                @else
                                    {!! AsForm::username(['name'=>'username','hiddenName' => 'login_username','addOnValue' => $organization->user_identifier,'value' => substr($user['username'],strlen($organization->user_identifier)+1) ,'parent' => 'col-sm-6 col-md-6 col-xs-12','class' => 'username']) !!}
                                @endif
                                {!! AsForm::text(['name' => 'email','parent' => 'col-md-6 col-xs-12 col-sm-6' , 'required' => true]) !!}
                            </div>
                            <div class="col-md-12 col-xs-12">
                                {!! AsForm::text(['name' => 'first_name','parent' => 'col-md-6 col-xs-12 col-sm-6' ,'required' => true]) !!}
                                {!! AsForm::text(['name' => 'last_name','parent' => 'col-md-6 col-xs-12 col-sm-6', 'required' => true]) !!}
                            </div>
                            <div class="col-md-12 col-xs-12">
                                {!! AsForm::text(['name' => 'permission', 'value' => $user_permission,'label' => 'Permission Role' ,'parent' => 'col-md-6 col-xs-12 col-sm-6', 'required' => true, 'attr' => ['readonly' => 'readonly']]) !!}
                                {!! AsForm::select(['name' => 'time_zone','data'=> $timeZone, 'empty_value' => 'Select Time Zone','value'  => $user['time_zone_id'] . ' : '. $user['time_zone'],'parent' => 'col-md-6 col-xs-12 col-sm-6']) !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label(null,'Profile Picture',['control-label']) }}
                                {{ Form::file('profile_picture',['form-control', 'id' => 'picture']) }}
                                <div class="description"><span>Please use jpg/jpeg/png/gif format and 150x150 dimensions image.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                @if($user['profile_picture'])
                                    <img src="{{ $user['profile_url'] }}" height="150" width="150" alt="{{ $user['profile_picture'] }}" id="selected_picture" style="border:1px solid black">
                                @else
                                    <img src="" height="150" width="150" alt="Uploaded Image" id="selected_picture" style="border:1px solid black" publi>
                                @endif
                            </div>
                            <hr/>
                            @if(Auth::user()->isAdmin())
                                <div>Organisation Secondary Contact</div>
                                <div class="form-group">
                                    {{ Form::label(null,'First Name') }}
                                    {{ Form::text('secondary_first_name',getVal((array)$organization->secondary_contact,['first_name']),['form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label(null,'Last Name') }}
                                    {{ Form::text('secondary_last_name',getVal((array)$organization->secondary_contact,['last_name']),['form-control']) }}
                                </div>
                                <div class="col-xs-12 col-md-12">
                                    {!! AsForm::text(['name' => 'secondary_email', 'label' => 'E-mail Address','value'=>getVal((array)$organization->secondary_contact,['email']),'parent' => 'col-sm-6 col-md-6 col-xs-12', 'required' => true]) !!}
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary btn-form btn-submit">Save</button>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('foot')
    <script src="{{url('js/chunk.js')}}"></script>
    <script>
        Chunk.displayPicture();
        var userIdentifier = '{{ $organization->user_identifier }}_';
        var username = $('.username').val();
        $('.login_username').val(userIdentifier + username);
        Chunk.usernameGenerator();
    </script>
@endsection
