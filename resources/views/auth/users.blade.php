<div class="input-wrapper">
    <p>Please provide the information below for the administrator of your organization’s account on AidStream.</p>
</div>

<div class="input-wrapper">
    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'users[username]', 'class' => 'username', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<div class="help-block">This was auto-generated using organization name abbreviation you provided earlier.</div>', 'attr' => ['readonly' => 'readonly', 'id' => 'username']]) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'users[first_name]', 'class' => 'first_name', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::text(['name' => 'users[last_name]', 'class' => 'last_name', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::email(['name' => 'users[email]', 'class' => 'email', 'label' => 'E-mail Address', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::password(['name' => 'users[password]', 'class' => 'password', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::password(['name' => 'users[confirm_password]', 'class' => 'confirm_password', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::email(['name' => 'users[secondary_contact]', 'class' => 'secondary_contact', 'label' => 'Secondary Contact at Organisation', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<p class="help-block">Example: example@email.com</p>']) !!}
    </div>
</div>

<div class="input-wrapper">
    <p>AidStream supports multiple user accounts for an organisation.</p>

    <div class="user-blocks">
        {{--*/ $users = getVal($regInfo, ['users', 'user'], []); /*--}}
        @foreach($users as $userIndex => $user)
            @include('auth.partUsers')
        @endforeach
    </div>
    {{ Form::button('Add a User', ['class' => 'btn btn-primary btn-submit btn-register', 'type' => 'button', 'id' => 'add-user']) }}
</div>

<div class="col-md-12 text-center">
    {{ Form::button('Back', ['class' => 'btn btn-primary btn-submit btn-register btn-tab', 'type' => 'button',  'data-tab-trigger' => '#tab-organization']) }}
    {{ Form::button('Complete Registration', ['class' => 'btn btn-primary btn-submit btn-register', 'type' => 'submit']) }}
</div>
