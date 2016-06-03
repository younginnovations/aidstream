@if(isset($multiple) && $multiple)
    @include('tz.project.partials.multiple-location')
@else
    @include('tz.project.partials.single-location')
@endif
