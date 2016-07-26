@extends('settings.settings')

@section('title', 'Register User')

@section('panel-body')
    <div class="create-form create-user-form settings-form">
        {{ Form::model(old(),['route' => 'admin.signup-user', 'method' => 'POST']) }}
        <span class="hidden" id="user-identifier" data-id="{{ $organizationIdentifier }}"></span>
        <div class="col-md-12">
            {!! AsForm::text(['name' => 'first_name', 'parent' => 'col-md-6', 'required' => true]) !!}
            {!! AsForm::text(['name' => 'last_name','parent' => 'col-md-6', 'required'=> true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::text(['name' => 'email','parent' => 'col-md-6','required'=> true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::text(['name' => 'username', 'parent' => 'col-md-6', 'required' => true, 'class' => 'username']) !!}
        </div>
        <div class="col-md-12">
            {!! AsForm::password(['name' => 'password','parent' => 'col-md-6', 'required' => true]) !!}
            {!! AsForm::password(['name' => 'password_confirmation', 'parent' => 'col-md-6', 'required' => true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::select(['name' => 'permissions', 'data' => $roles, 'empty_value' => 'Please select a permission',null, 'parent' => 'col-md-6' , 'required' => true]) !!}
        </div>
        <div class="form-group">
            {{ Form::submit('Create',['class'=>'btn btn-primary btn-form btn-submit']) }}
            <a href="{{route('admin.list-users')}}" class="btn btn-cancel">
                Cancel
            </a>
        </div>
        {{ Form::close() }}
    </div>
@endsection
@section('foot')
    <script src="{{url('js/chunk.js')}}"></script>
    <script>
        Chunk.usernameGenerator();
    </script>
@endsection
