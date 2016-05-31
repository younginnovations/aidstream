@if (hasSubdomain())
    @include('tz.user.editProfile')
@else
    @include('User.partials.editProfile')
@endif
