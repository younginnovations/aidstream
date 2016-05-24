@if($btn_text != "")
    <form method="POST" id="change_status" class="pull-right" action="{{ $nextRoute }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="activity_workflow" value="{{ $activityWorkflow + 1 }}">
        @if($activityWorkflow == 2)
            <input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
                   data-title="Confirmation" data-message="Are you sure you want to Publish?">
        @else
            <input type="submit" value="Mark as {{ $btn_text }}">
        @endif
    </form>
@endif
