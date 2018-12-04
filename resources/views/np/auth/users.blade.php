<div class="registration-inner-wrapper">
    <div class="text-wrapper">
        <h2>@lang('global.administrator_information')</h2>
        <p>@lang('global.please_provide_details_administrator')</p>
    </div>

    <div class="input-wrapper">
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'users[username]', 'help' => 'registration_admin_username', 'class' => 'username', 'label'=>trans('user.username'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'attr' => ['readonly' => 'readonly', 'id' => 'username']]) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::password(['name' => 'users[password]', 'class' => 'password', 'label' => trans('user.password'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::password(['name' => 'users[confirm_password]', 'class' => 'confirm_password', 'label' => trans('user.confirm_password'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'users[first_name]', 'class' => 'first_name', 'label' => trans('user.first_name'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::text(['name' => 'users[last_name]', 'class' => 'last_name', 'label' => trans('user.last_name'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::email(['name' => 'users[email]', 'class' => 'email', 'label' => trans('user.email_address'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {{-- {!! AsForm::email(['name' => 'users[secondary_contact]', 'class' => 'secondary_contact', 'label' => trans('global.secondary_contact_at_organisation'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => sprintf('<p class="help-block">%s: example@email.com</p>', trans('global.example'))]) !!} --}}
        </div>
    </div>
    <div class="user-blocks">
        {{--*/ $users = getVal($regInfo, ['users', 'user'], []); /*--}}
        @foreach($users as $userIndex => $user)
            @include('auth.partUsers')
        @endforeach
    </div>
    <div class="auth-info-wrapper">
        @lang('global.aidstream_supports_multiple')
    </div>
</div>
{{ Form::button(trans('global.back_to_organisation_information'), ['class' => 'btn btn-primary btn-back btn-tab pull-left', 'type' => 'button',  'data-tab-trigger' => '#tab-organization']) }}
{{ Form::button(trans('global.complete_registration'), ['class' => 'btn btn-primary btn-submit btn-register pull-right', 'type' => 'submit']) }}
