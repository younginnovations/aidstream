@if($btn_text != "")
    <form method="POST" id="change_status" action="{{ $nextRoute }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="activity_workflow" value="{{ $activityWorkflow + 1 }}">
        @if($activityWorkflow == 2)
            <input type="button" value="Mark this activity as {{ trans(sprintf('lite/global.%s',strtolower($btn_text)))}}" class="btn_confirm"
                   data-title="{{trans('lite/global.confirmation')}}" data-message="{{trans('lite/global.are_you_sure_to_publish')}}">
        @else
            <input type="submit" value="Mark this activity as {{ trans(sprintf('lite/global.%s',strtolower($btn_text)))}}">
        @endif
    </form>
@else
    {{--<div class="popup-link-content">--}}
        {{--<a href="#" title="{{ucfirst($activityPublishedStatus)}}"--}}
           {{--class="{{ucfirst($activityPublishedStatus)}}">{{ucfirst($activityPublishedStatus)}}</a>--}}
        {{--<div class="link-content-message">{!!$message!!}</div>--}}
    {{--</div>--}}
@endif