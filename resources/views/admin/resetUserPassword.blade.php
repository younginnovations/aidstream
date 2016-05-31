@if (hasSubdomain())
    @include('tz.user.resetPassword')
@else
    @include('admin.partials.resetPassword')
@endif
