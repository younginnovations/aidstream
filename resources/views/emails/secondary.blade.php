<p>
    Hi {{ $email }},
</p>
<p>
    One of your colleagues - {{ $admin['first_name'] }} {{ $admin['last_name'] }} - has registered {{ $org_name }} with AidStream, a handy tool which makes it simple for you to publish your aid data
    to the global IATI Registry without any fuss.
</p>
<p>
    They have provided your email address as a secondary / back-up address for their new administrator account, in order to ensure that you can recover your organisation’s account data in the case
    that that the admin login credentials are lost or there is a change/loss of administrator.
</p>
<p>
    To verify your email address and confirm it as your organisation’s backup account on AidStream, simply click the link below.
</p>
<p>
<p><a href="{{ route('secondary-verification',  [$verification_code]) }}">Back up {{ $org_name }}'s AidStream account</a></p>
</p>
<p>
    Once you’ve confirmed your email address we’ll store it so that it can be used as a retrieval email address if you ever need it.
</p>
<p>
    If you have any questions, feel free to reach out; you’ll find us at <a href="mailto:support@aidstream.org">support@aidstream.org</a>
</p>
<p>
    Happy data publishing!
</p>
<p>
    Your AidStream Team
</p>
