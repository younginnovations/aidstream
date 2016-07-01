@extends('Activity.activityBaseTemplate')

@section('title', 'Activity Data')

@inject('getCode', 'App\Helpers\GetCodeName')

@section('activity-content')
    <?php
    $activity_workflow = $activityDataList['activity_workflow'];
    $status_label = ['draft', 'completed', 'verified', 'published'];
    $btn_status_label = ['Completed', 'Verified', 'Published'];
    $btn_text = $activity_workflow > 2 ? "" : $btn_status_label[$activity_workflow];
    ?>
    <div class="element-panel-heading">
        <div>
            <span>{{ $activityDataList['title'] ? $activityDataList['title'][0]['narrative'] : 'No Title' }}</span>
            <div class="element-panel-heading-info">
                <span>{{$activityDataList['identifier']['iati_identifier_text']}}</span>
                <span class="last-updated-date">Last Updated on: {{changeTimeZone($activityDataList['updated_at'], 'M d, Y H:i')}}</span>
            </div>
            <div class="view-xml-btn"><a href="{{route('view.activityXml', ['activityId' => $id])}}">View IATI XML file</a></div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
        <div class="activity-status activity-status-{{ $status_label[$activity_workflow] }}">
            <ol>
                @foreach($status_label as $key => $val)
                    @if($key == $activity_workflow)
                        <li class="active"><span>{{ $val }}</span></li>
                    @else
                        <li><span>{{ $val }}</span></li>
                    @endif
                @endforeach
            </ol>
            @if($btn_text != "")
                <form method="POST" id="change_status" class="pull-right" action="{{ $nextRoute }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                    @if($activity_workflow == 2)
                        <input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
                               data-title="Confirmation" data-message="Are you sure you want to Publish?">
                    @else
                        <input type="submit" value="Mark as {{ $btn_text }}">
                    @endif
                </form>
            @else
                <div class="popup-link-content">
                    <a href="#" title="{{ucfirst($activityPublishedStatus)}}" class="{{ucfirst($activityPublishedStatus)}}">{{ucfirst($activityPublishedStatus)}}</a>
                    <div class="link-content-message">{!!$message!!}</div>
                </div>
            @endif
        </div>
        <a href="{{route('change-activity-default', $id)}}" class="pull-right">
            <span class="glyphicon glyphicon-triangle-left"></span> Override Activity Default
        </a>
        @include('Activity.partials.elements')
    </div>
@endsection
@section('foot')
    <script>
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({html: true});
        });
    </script>
@endsection
