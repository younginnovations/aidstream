<div class="user-block clearfix">
    <a href="#delete" class="delete pull-right">@lang('global.remove')</a>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'users[user][' . $userIndex . '][username]', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'class' => 'username', 'label' => trans('user.username')]) !!}
        {!! AsForm::email(['name' => 'users[user][' . $userIndex . '][email]', 'class' => 'email', 'label' => trans('user.email_address'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'users[user][' . $userIndex . '][first_name]', 'class' => 'first_name', 'label' => trans('user.first_name'),'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::text(['name' => 'users[user][' . $userIndex . '][last_name]', 'class' => 'last_name', 'label' => trans('user.last_name'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
    <div class="col-xs-12 col-md-12">
        {!! AsForm::select(['name' => 'users[user][' . $userIndex . '][role]', 'class' => 'role', 'label' => trans('user.permission_role'), 'help' => 'user_permission_role', 'data' => $roles, 'empty_value' => trans('global.select_a_role'), 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
</div>
