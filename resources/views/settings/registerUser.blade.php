@extends('settings.settings')

@section('title', trans('title.register_user'))

@section('panel-body')
    <div class="create-form create-user-form settings-form users-form">
        {{ Form::model(old(),['route' => 'admin.signup-user', 'method' => 'POST']) }}
        <span class="hidden" id="user-identifier" data-id="{{ $organizationIdentifier }}"></span>
        <div class="col-md-12">
            {!! AsForm::text(['name' => 'first_name','label' =>trans('user.first_name'),'parent' => 'col-sm-6', 'required' => true]) !!}
            {!! AsForm::text(['name' => 'last_name','label' =>trans('user.last_name'),'parent' => 'col-sm-6', 'required'=> true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::text(['name' => 'email','label' =>trans('user.email'),'parent' => 'col-sm-6','required'=> true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::text(['name' => 'username', 'label' =>trans('user.username'),'parent' => 'col-sm-6', 'required' => true, 'class' => 'username']) !!}
        </div>
        <div class="col-md-12">
            {!! AsForm::password(['name' => 'password','label' =>trans('user.password'),'parent' => 'col-sm-6', 'required' => true]) !!}
            {!! AsForm::password(['name' => 'password_confirmation', 'label' =>trans('user.confirm_password'),'parent' => 'col-sm-6', 'required' => true]) !!}
        </div>
        <div class="col-md-12 single-form-wrap">
            {!! AsForm::select(['name' => 'permission', 'label' =>trans('user.permission'),'data' => $roles, 'empty_value' => trans('setting.please_select_a_permission'),null, 'parent' => 'col-sm-6' , 'required' => true]) !!}
        </div>
        <div class="form-group">
            {{ Form::submit(trans('global.create'),['class'=>'btn btn-primary btn-form btn-submit']) }}
            <a href="{{route('admin.list-users')}}" class="btn btn-cancel">
                @lang('global.cancel')
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
