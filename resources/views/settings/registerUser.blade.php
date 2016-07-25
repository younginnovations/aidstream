@extends('settings.settings')

@section('title', 'Register User')

@section('panel-body')
    <div class="panel-content-heading">User Information</div>
    <div class="create-form create-user-form">
        {{ Form::model(old(),['route' => 'admin.signup-user', 'method' => 'POST']) }}
        <div class="panel-body">
            <div class="col-md-12">
                {!! AsForm::text(['name' => 'first_name', 'parent' => 'col-md-6', 'required' => true]) !!}
                {!! AsForm::text(['name' => 'last_name','parent' => 'col-md-6', 'required'=> true]) !!}
            </div>
            <div class="col-md-12">
                {!! AsForm::text(['name' => 'email','parent' => 'col-md-6','required'=> true]) !!}
            </div>
            <div class="col-md-12">
                <span>{{$organizationIdentifier}}</span>
                {!! AsForm::text(['name' => 'username', 'parent' => 'col-md-6', 'required' => true]) !!}
            </div>
            <div class="col-md-12">
                {!! AsForm::password(['name' => 'password','parent' => 'col-md-6', 'required' => true]) !!}
                {!! AsForm::password(['name' => 'password_confirmation', 'parent' => 'col-md-6', 'required' => true]) !!}
            </div>
            <div class="col-md-12">
                {!! AsForm::select(['name' => 'permissions', 'data' => $roles, 'empty_value' => 'Please select a permission',null, 'parent' => 'col-md-6' , 'required' => true]) !!}
            </div>
        </div>
        {{ Form::submit('Create',['class'=>'btn btn-primary btn-form btn-submit']) }}
        <a href="{{route('admin.list-users')}}" class="btn btn-cancel">
            Cancel
        </a>
        {{ Form::close() }}
    </div>
@endsection
