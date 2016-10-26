<p>
    Thank you for verifying your email address. Please set a password for your account.
</p>
{{ Form::open(['url' => route('create-password', [$user->verification_code]), 'method' => 'post']) }}

<div class="col-xs-12 col-md-12">
    {!! AsForm::password(['name' => 'password', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    {!! AsForm::password(['name' => 'confirm_password', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
</div>

<div class="col-md-12 text-center">
    {{ Form::button('Create Password', ['class' => 'btn btn-primary btn-submit btn-register', 'type' => 'submit']) }}
</div>

{{ Form::close() }}
