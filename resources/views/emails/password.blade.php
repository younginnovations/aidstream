<p>Dear {{ $user->first_name }} {{ $user->last_name }},</p>
<p>You have requested to reset your account password.You may do so by clicking on the link below or copying and pasting it in your browser:</p>
<p><a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a></p>
<p>Your Aidstream username: <strong>{{ $user->username }}</strong> <br/></p>
<p>Thank you.</p>
<p>------ <br/>AidStream</p>
