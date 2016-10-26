<p>
    Hi {{ $first_name }},
</p>
<p>
    Thanks for signing up and welcome to AidStream! To verify your email address and associate it with your organisation’s main account on AidStream, simply click the link below.
</p>
<p><a href="{{ route('user-verification',  [$verification_code]) }}">Connect Me!</a></p>
<p>
    Once you’ve confirmed your email address and are logged in to AidStream, feel free to have a look around and get familiar with the system. We’ve even got a handy onboarding introduction waiting
    for you when you first log in - it should help you find your feet!
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
