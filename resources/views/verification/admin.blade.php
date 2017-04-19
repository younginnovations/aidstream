@inject('code', 'App\Helpers\GetCodeName')

<ul>
    @foreach($users as $orgUser)
        <li>
            <strong>{{ title_case($orgUser->role) }} @lang('user.account'):</strong> {{ $orgUser->first_name }} {{ $orgUser->last_name }}
            {{--(Username: {{ $user->username }}) - {{ $user->email }}--}}
        </li>
    @endforeach
</ul>

{{--<ul>--}}
{{--<li><strong>Administrator Account:</strong> test</li>--}}
{{--<li><strong>Editor Account:</strong> test</li>--}}
{{--</ul>--}}
<p>
    @lang('registration.thank_you_for_verifying_your_email',['organisation_name' => $user->organization->name])</p>
{{ Form::open(['url' => route('save-registry-info', [$user->verification_code]), 'method' => 'post']) }}
<div class="save-registry-block">
    <p>
        @lang('registration.add_the_publisher_id')
    </p>

    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'publisher_id', 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::text(['name' => 'api_id', 'label' => trans('user.api_key'), 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
</div>

<div class="col-md-12 text-center">
    {{ Form::button(trans('global.save_and_continue'), ['class' => 'btn btn-primary pull-left', 'type' => 'submit']) }}
    {{ Form::button(trans('global.i_will_add_this_later'), ['class' => 'btn btn-primary pull-right', 'type' => 'button', 'id' => 'add-this-later', 'data-code' => $user->verification_code]) }}
</div>

{{ Form::close() }}

<p>
    @lang('user.you_can_also_add_this_information')
</p>
<p>
    @lang('global.thank_you_for_choosing_aidstream_long_text')
</p>
