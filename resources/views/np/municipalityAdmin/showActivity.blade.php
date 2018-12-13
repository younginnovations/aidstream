@extends('np.municipalityAdmin.includes.base')

@section('title', @trans('lite/title.activities'))

@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper activity__detail__wrapper">
        @include('includes.response')
        <div class="panel__heading">
            <div class="panel__heading__info pull-left">
                <div class="panel__title">
                    @lang('lite/global.activity_detail')
                </div>
                <a href="{{ route('municipalityAdmin.list-activities') }}" class="back-to-activities-list">@lang('lite/global.back_to_activities_list')</a>
            </div>
            <a href="" class="pull-right print">Print</a>
        </div>
        <div class="panel__body">
            <div class="col-xs-12 col-sm-9 panel__activity__detail">
                <h1 class="activity__title">
                    {{ $activity->title ? $activity->title[0]['narrative'] : trans('lite/global.no_title') }}
                </h1>
                <div class="activity-iati-info">
                    <div class="pull-left iati-identifier-wrapper">@lang('lite/global.iati_identifier'):
                        <span class="iati-identifier">{{ $activity->identifier['activity_identifier'] }}</span>
                    </div>
                </div>
                @if($activity->activity_date || $activity->activity_status)
                    <div class="activity-info activity-more-info">
                        <ul class="pull-left">
                            @if($activity->activity_date)
                                <li>
                                    <i class="pull-left material-icons">date_range</i>
                                    @foreach (getVal($activity->toArray(), ['activity_date'], []) as $date)
                                        @if(getVal($date, ['type']) == 2)
                                            <span>  {{ formatDate($date['date']) }} </span>
                                        @endif

                                        @if(getVal($date, ['type']) == 4)
                                            <span> {{ formatDate($date['date']) }} </span>
                                        @endif
                                    @endforeach
                                </li>
                            @endif
                            @if($activity->activity_status)
                                <li>
                                    <i class="pull-left material-icons">autorenew</i>
                                    <span>{{  $getCode->getCodeNameOnly('ActivityStatus', $activity->activity_status) }}<i> (Status)</i></span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                @include('np.municipalityAdmin.partials.activityList')
            </div>
            <div class="col-xs-12 col-sm-3 panel__activity__more--info">
                <div class="activity__block activity__status activity-status-{{ $statusLabel[$activityWorkflow] }}">
                    <h4>@lang('lite/global.activity_status')</h4>
                    @foreach($statusLabel as $key => $value)
                        @if($key == $activityWorkflow)
                            <div class="active"><span>{{ trans(sprintf('lite/global.%s',strtolower($value)))}}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="activity__block activity__updated__date">
                    <span class="last-updated-date"><i>@lang('lite/global.last_updated_on'): {{ changeTimeZone($activity['updated_at'], 'M d, Y H:i') }}</i></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section ('script')
    <script src="/js/d3.min.js"></script>
    <script>
        var recipientCountries = {!!json_encode(array_flip($recipientCountries))!!};
        var pathColorCode = "#D9E5EB";
        var recipientCountryColorCode = "#00A8FF";
    </script>
    <script>
        $(document).ready(function () {
            function fixedTop() {
                var fixmeTop = $('.panel__activity__more--info').offset().top - 61;
                $(window).scroll(function () {
                    var currentScroll = $(window).scrollTop();
                    if (currentScroll >= fixmeTop) {
                        $('.panel__activity__more--info').addClass('fixed');
                    } else {
                        $('.panel__activity__more--info').removeClass('fixed');
                    }
                });
            }

            fixedTop();
            $(window).resize(function () {
                fixedTop();
            });
        });
    </script>
    <script>
    $(document).ready(function(){
        $('.activities>a').addClass('active');
    });
    </script>
@stop
