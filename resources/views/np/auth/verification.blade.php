<div class="registration-inner-wrapper">
    <div class="col-md-12 text-center verification-wrapper">
        <img src={{ url('/images/ic-sent-mail.svg') }} alt="mail" width="88" height="94">
        <h1>@lang('global.thank_you')!</h1>
        <p>
            @lang('global.verification_email_has_been_sent') <strong>{{ session('email') }}</strong>. @lang('registration.please_click_on_the_link_to_verify')
        </p>
    </div>
</div>

