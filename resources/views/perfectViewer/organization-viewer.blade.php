<!DOCTYPE html>
<html>

@inject('codeListHelper', 'App\Helpers\GetCodeName')

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Organization Viewer</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <link rel="stylesheet" href="{{asset('/css/vendor.min.css')}}">
    {!! publicStylesheet() !!}
</head>

<style type="text/css">
    .bar {
        fill: #B2BEC4;
        font-size: 14px;
        cursor: pointer;
    }

    rect.bar:hover {
        fill: #00A8FF !important;
    }

    .axis {
        font: 14px sans-serif;
        fill: #979797;
    }

    .axis path,
    .axis line {
        fill: none;
        shape-rendering: crispEdges;
    }
</style>

<body>
<div class="org-header-wrapper">
    @if (isTzSubDomain())
        @include('tz.partials.header')
    @elseif(isNpSubDomain())
        <section class="header-banner">
        @include('np.partials.header')
        </section>
    @else
        @include('includes.header')
    @endif
</div>
<div class="wrapper">
    @include('includes.response')
    <div id="map"></div>
    <div id="tooltip" class="tooltips"></div>
    <section class="col-md-12 org-map-wrapper pull-left">
        <div class="width-940">
            <div class="col-sm-6 col-md-5 organisation-info">
                <div class="logo">
                    @if($organizations['logo'])
                        <a href="#" class="organisation-logo">
                            <img src="{{ $organizations['logo_url'] }}" alt="{{ $organizations['name'] }}" width="auto" height="68">
                        </a>
                    @endif
                </div>
                <div class="organisation-more-info">
                    <span class="organisation-name">
                        <a href="#" title="{{ getVal($organizations, ['name'], '')}}">
                            {{ getVal($organizations, ['reporting_org', 0, 'narrative', 0, 'narrative'], '')}}
                            {{--{{$organizations['name']}}--}}
                        </a>
                    </span>
                    @if($organizations['address'])
                        <address><i class="pull-left material-icons">room</i>{{$organizations['address']}}</address>
                    @endif
                    <a href="{{url('/who-is-using')}}" class="see-all-activities"><i class="pull-left material-icons">arrow_back</i>@lang('perfectViewer.see_all_organisations')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="col-md-12 org-main-wrapper">
        <div class="width-940" data-sticky_parent>
            <div class="col-xs-12 col-sm-4 col-md-4 org-main-transaction-wrapper pull-right" data-sticky_column>
                <div class="org-transaction-wrapper">
                    <ul>
                        <li>
                            <h4>@lang('perfectViewer.total_commitments')</h4>
                            <span>
                                ${{moneySuffix(getVal($organizations, ['transaction_totals', 'total_commitments'], 0))}}
                            </span>
                        </li>
                        <li>
                            <h4>@lang('perfectViewer.total_disbursements')</h4>
                            <span>
                                ${{moneySuffix(getVal($organizations, ['transaction_totals', 'total_disbursements'], 0))}}
                            </span>
                        </li>
                        <li>
                            <h4>@lang('perfectViewer.total_expenditures')</h4>
                            <span>
                                ${{moneySuffix(getVal($organizations, ['transaction_totals', 'total_expenditures'], 0))}}
                            </span>
                        </li>
                        <li>
                            <h4>@lang('perfectViewer.total_incoming_funds')</h4>
                            <span>
                                ${{moneySuffix(getVal($organizations, ['transaction_totals', 'total_incoming_funds'], 0))}}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8 org-activity-wrapper pull-left" data-sticky_column>
                @if(count($activities) <= 0)<h2>@lang('global.activities') <span class="activity-count">({{count($activities)}})</span></h2>@endif
                <ul class="activities-listing">
                    @foreach($activities as $index => $activity)
                        <li>
                            <a href="{{url('/who-is-using/'.$organizations['org_slug'].'/'.$activity['activity_id'])}}">
                                <div class="col-xs-12 col-sm-9 col-md-9 pull-left activity-info-wrapper">
                                    <h3 class="activity-name">
                                        {{getVal($activity, ['published_data', 'title', 0, 'narrative'], 'Not Available')}}
                                        @if(getVal($activity, ['activity_in_registry'], null))
                                            <img src="{{asset('images/ic-iati-logo.png')}}" alt="IATI" width="20" height="19">
                                        @endif
                                    </h3>
                                    @if(getVal($activity, ['published_data', 'identifier', 'activity_identifier'], null))
                                        <div class="iati-identifier-wrapper">@lang('perfectViewer.iati_identifier'):
                                            <span class="iati-identifier">
                                                {{getVal($activity, ['published_data', 'identifier', 'iati_identifier_text'], '')}}
                                        </span>
                                        </div>
                                    @endif
                                    <div class="activity-info">
                                        <ul class="pull-left">
                                            @if(getVal($activity, ['published_data', 'activity_date']))
                                                <li><i class="pull-left material-icons">date_range</i>
                                                    @foreach(getVal($activity, ['published_data', 'activity_date'], []) as $index => $date)
                                                        <span>
                                                            @if(getVal($date, ['type'], 0) == 2)
                                                                {{dateFormat('M d, Y', getVal($date, ['date'], ''))}}
                                                                @break
                                                            @elseif(getVal($date, ['type'], 0) == 1)
                                                                {{dateFormat('M d, Y', getVal($date, ['date'], ''))}}
                                                                @break
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                    @foreach(getVal($activity, ['published_data', 'activity_date'], []) as $index => $date)
                                                        <span>
                                                            @if(getVal($date, ['type'], 0) == 4)
                                                                - {{dateFormat('M d, Y', getVal($date, ['date'], ''))}}
                                                                @break
                                                            @elseif(getVal($date, ['type'], 0) == 3)
                                                                - {{dateFormat('M d, Y', getVal($date, ['date'], ''))}}
                                                                @break
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </li>
                                            @endif
                                            @if(getVal($activity, ['published_data', 'activity_status'], null))
                                                <li>
                                                    <i class="pull-left material-icons">autorenew</i>
                                                    <span>
                                                        {{ $codeListHelper->getCodeNameOnly('ActivityStatus', getVal($activity, ['published_data', 'activity_status'], '')) }}
                                                        <i>(@lang('perfectViewer.status'))</i>
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 pull-right total-budget-wrapper">
                                    <span>@lang('perfectViewer.total_budget')</span>
                                    <span class="total-budget-amount">{{number_format(round(getVal($activity, ['published_data', 'totalBudget', 'value'], 0), 2))}}</span>
                                    <span class="currency">USD</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    @if(isTzSubDomain())
	@include('tz.partials.footer')
@elseif(isNpSubDomain())
	@include('np.partials.footer')
@else
	@include('includes.footer')
@endif
</div>
</body>
<script src="/js/jquery.js"></script>
<script src="/js/jquery-stickykit.js"></script>
<script src="/js/d3.min.js"></script>
<script type="text/javascript" src="/js/worldMap.js"></script>
<script type="text/javascript" src="/js/publicPages.js"></script>
<script>
    var recipientCountries = {!!json_encode(array_flip($recipientCountries))!!};

    var pathColorCode = "#D9E5EB";
    var recipientCountryColorCode = "#00A8FF";

    @if(isTzSubDomain())
        pathColorCode = "#DAEBDE";
        recipientCountryColorCode = "#1AAB3C";
    @endif

    $(document).ready(function () {
        function sidebarStick() {
            if ($(window).width() > 768) {
                var contentHeight = $('.org-activity-wrapper').height();
                var sidebarHeight = $('.org-main-transaction-wrapper').height();
                if (contentHeight > sidebarHeight) {
                    $('.org-main-transaction-wrapper').height(contentHeight);
                    $(".org-main-transaction-wrapper .org-transaction-wrapper").stick_in_parent();
                }
            }
            else {
                $('.org-main-transaction-wrapper').height('auto');
            }
        }

        sidebarStick();

        $(window).resize(function () {
            sidebarStick();
        });
    });
</script>
</html>
