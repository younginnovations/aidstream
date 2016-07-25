@inject('code', 'App\Helpers\GetCodeName')

<p>Your account is now verified. You have successfully created the following user accounts for your organisation:</p>
<ol>
    @foreach($users as $user)
        <li>{{ title_case($user->role) }}: {{ $user->first_name }} {{ $user->last_name }} (Username: {{ $user->username }}) - {{ $user->email }}</li>
    @endforeach
</ol>
<p>
    For any accounts you have created other than your own, we have sent a verification email to those users. If you provided a back up contact for account recovery, we have also sent a verification
    email to that address. Please ask all additional users to check their email inbox and follow the instructions in our emails.</p>
<p>
    To publish information to the IATI Registry you need to enter your Publisher ID and the API key provided by the Registry.
</p>
{{ Form::open(['url' => route('save-registry-info', [$user->verification_code]), 'method' => 'post']) }}

<div class="col-xs-12 col-md-12">
    {!! AsForm::text(['name' => 'publisher_id', 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
    {!! AsForm::text(['name' => 'api_id', 'label' => 'API Key', 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
</div>

<div class="col-md-12 text-center">
    {{ Form::button('Save Registry Info', ['class' => 'btn btn-primary btn-submit btn-register', 'type' => 'submit']) }}
</div>

{{ Form::close() }}
<p>
    You can also add this information via your Settings page, after you login.
</p>
<p>
    Thank you for choosing AidStream to help you publish your data to the IATI Registry. Please login to learn more about using AidStream.If you need any help, you can contact us at
    <a href="mailto:support@aidstream">support@aidstream.org</a>
</p>
