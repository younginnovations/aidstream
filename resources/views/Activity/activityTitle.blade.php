<input type="checkbox" name="activities[]"
       value="{{ json_encode($activity['data']) }}" class="pull-left"
       @if($activity['errors'] || $isDuplicate) disabled="disabled" @endif>
<div class="activity-title">
    @if($title = $activity['data']['activity_title'])
        {{ $title }}
    @else
        <div class="no-title">(No Title)</div>
    @endif
</div>
{{--<div class="activity-identifier">--}}
{{--{{ $activity['data']['activity_identifier'] }}--}}
{{--</div>--}}
