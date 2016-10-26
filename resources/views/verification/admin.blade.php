@inject('code', 'App\Helpers\GetCodeName')

<p>Thank you for verifying your email address. You have successfully created an account for {{ $user->organization->name }} and
    associated the following user accounts with it:</p>
<ul>
    @foreach($users as $orgUser)
        <li>
            <strong>{{ title_case($orgUser->role) }} Account:</strong> {{ $orgUser->first_name }} {{ $orgUser->last_name }}
            {{--(Username: {{ $user->username }}) - {{ $user->email }}--}}
        </li>
    @endforeach
</ul>

{{--<ul>--}}
{{--<li><strong>Administrator Account:</strong> test</li>--}}
{{--<li><strong>Editor Account:</strong> test</li>--}}
{{--</ul>--}}
<p>
    For any accounts you have created other than your own, we have sent a verification email to those users. If you
    provided a back up contact for account recovery, we have also sent a verification
    email to that address. Please ask all additional users to check their email inbox and follow the instructions in our
    emails.</p>
{{ Form::open(['url' => route('save-registry-info', [$user->verification_code]), 'method' => 'post']) }}
<div class="save-registry-block">
    <p>
        Now that your AidStream account is live, add the Publisher ID and API Key which the IATI Registry provided when
        you
        registered with them and you can instantly start publishing your data through AidStream.
    </p>

    <div class="col-xs-12 col-md-12">
        {!! AsForm::text(['name' => 'publisher_id', 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        {!! AsForm::text(['name' => 'api_id', 'label' => 'API Key', 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    </div>
</div>

<div class="col-md-12 text-center">
    {{ Form::button('Save and Continue', ['class' => 'btn btn-primary pull-left', 'type' => 'submit']) }}
    {{ Form::button('I will add this later', ['class' => 'btn btn-primary pull-right', 'type' => 'submit']) }}
</div>

{{ Form::close() }}

<p>
    You can also add this information via your Settings page, after you have logged in.
</p>
<p>
    Thank you for choosing AidStream as your IATI data publishing tool. We're always happy to help; simply drop us an email at
    <a href="mailto:support@aidstream">support@aidstream.org</a> and we'll get back to you. To learn more about how to use AidStream, simply log in to your new account.
</p>
