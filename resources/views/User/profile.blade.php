@if (hasSubdomain())
    @include('tz.user.profile')
@else
    @include('User.partials.profile')
@endif
