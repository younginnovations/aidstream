@extends('Activity.activityBaseTemplate')

@section('title', trans('title.activity_data'))

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
                <span class="last-updated-date">@lang('global.last_updated_on'): {{changeTimeZone($activityDataList['updated_at'], 'M d, Y H:i')}}</span>
            </div>
            <div class="view-xml-btn"><a href="{{route('view.activityXml', ['activityId' => $id])}}">@lang('global.view_iati_xml_file')</a></div>
        </div>
    </div>
    @if(!getVal($activityDataList,['imported_from_xml']))
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
            <div class="activity-status activity-status-{{ $status_label[$activity_workflow] }}">
                <ol>
                    @foreach($status_label as $key => $val)
                        @if($key == $activity_workflow)
                            <li class="active"><span>{{ trans(sprintf('global.%s',$val)) }}</span></li>
                        @else
                            <li><span>{{ trans(sprintf('global.%s',$val)) }}</span></li>
                        @endif
                    @endforeach
                </ol>
                @if($btn_text != "")
                    <form method="POST" id="change_status" class="pull-right" action="{{ $nextRoute }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                        @if($activity_workflow == 2)
                            <input type="button" value="{{trans('global.mark_as')}} {{ trans(sprintf('global.%s',strtolower($btn_text))) }}" class="btn_confirm"
                                   data-title="Confirmation" data-message="{{ trans('global.are_you_sure_to_publish') }}">
                        @else
                            <input type="submit" value="{{trans('global.mark_as')}} {{ trans(sprintf('global.%s',strtolower($btn_text )))}}">
                        @endif
                    </form>
                @else
                    <div class="popup-link-content">
                        <a href="#" title="{{ucfirst($activityPublishedStatus)}}"
                           class="{{ucfirst($activityPublishedStatus)}}">{{ucfirst($activityPublishedStatus)}}</a>
                        <div class="link-content-message">{!!$message!!}</div>
                    </div>
                @endif
            </div>
            <a href="" class="pull-right print">@lang('global.print')</a>
            <a href="{{route('change-activity-default', $id)}}" class="pull-right override-activity">
                <span class="glyphicon glyphicon-triangle-left"></span> @lang('global.override_activity_default')
            </a>
            @include('Activity.partials.elements')
        </div>
    @else
        <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper xml-import-status-wrapper">
            <div class="xml-import-wrapper">
                <p> @lang('error.fix_activity')<span class="show-error-link">@lang('global.show_errors')</span></p>
                <div class="error-listing">
                    @foreach($errors as $element =>$errorIndex)
                        <div class="error-list">
                            <label>{{$element}}</label>
                            @foreach($errorIndex as $error)
                                <p><a href="{{ $error['link'] }}">{{ $error['message'] }}</a></p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            <a href="" class="pull-right print">@lang('global.print')</a>
            <a href="{{route('change-activity-default', $id)}}" class="pull-right override-activity">
                <span class="glyphicon glyphicon-triangle-left"></span> @lang('global.override_activity_default')
            </a>
            @include('Activity.partials.elements')
        </div>
    @endif
@endsection
@section('foot')
    <script>
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({html: true});
        });
    </script>
@endsection
