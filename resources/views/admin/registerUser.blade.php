@if (hasSubdomain())
    @include('tz.user.create')
@else
    @include('admin.partials.create')
@endif
