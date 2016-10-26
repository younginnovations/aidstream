<p>Hi {{ $user->first_name }} {{ $user->last_name }},</p>
<p>We’ve received a request to reset your AidStream account password. Click the button below to reset it.</p>
<p><a href="{{ url('password/reset/'.$token) }}">Reset Password</a></p>
<p>If you didn’t request this password reset, you can safely ignore this email and no changes will be made to your account.</p>
<p>If you have any questions, you can reach us at <a href="mailto:support@aidstream.org">support@aidstream.org</a></p>
<p>Thanks, <br/>
    The AidStream Team</p>
