@if (hasSubdomain())
    @include('tz.users')
@else
    @include('admin.partials.listUsers')
@endif
