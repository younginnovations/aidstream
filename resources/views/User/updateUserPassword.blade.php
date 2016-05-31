@if (hasSubdomain())
    @include('tz.user.updateUserPassword')
@else
    @include('User.partials.updateUserPassword')
@endif
