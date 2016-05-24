@if (hasSubdomain())
    @include('tz.user-view')
@else
    @include('admin.userProfile')
@endif
