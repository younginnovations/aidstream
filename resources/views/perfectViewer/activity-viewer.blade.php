<!doctype html>
<html lang="en">

@inject('codeListHelper', 'App\Helpers\GetCodeName')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">

    <meta name="title" content="Activity Viewer"/>
    <meta name="description"
          content="AidStream is an online platform for organisations that wish to publish aid data in accordance with the International Aid Transparency Initiative(IATI) format but want to avoid dealing with the complexities of creating XML."/>
    <meta name="robots" content="index,follow"/>
    <meta name="copyright" content="AidStream"/>

    <meta name="og:site_name" content="Aidstream"/>
    <meta name="og:title"
          content="Activity Viewer - {{ getVal($activity, [0, 'published_data', 'identifier', 'iati_identifier_text']) }} - {{ getVal($activity, [0, 'published_data', 'title', 0, 'narrative'], '') }}"/>
    <meta name="og:image" content="{{ url('images/aidstream_logo.png') }}"/>
    <meta name="og:description" content="{{ getVal($activity, [0, 'published_data', 'description']) }}"/>
    <meta name="og:type" content="website"/>
    <meta name="og:url" content="{{ url()->current() }}"/>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
          content="Activity Viewer - {{ getVal($activity, [0, 'published_data', 'title', 0, 'narrative'], '') }}">
    <meta name="twitter:description" content="{{ getVal($activity, [0, 'published_data', 'description']) }}">
    <meta name="twitter:image:src" content="{{ url('images/aidstream_logo.png') }}"/>
    <meta name="twitter:url" content="{{ url()->current() }}"/>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/vendor.min.css')}}">
    {!! publicStylesheet() !!}
    <title>@lang('title.activity_viewer')</title>
</head>
<body>
<div class="activity-header-wrapper">
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
    <div id="tooltip" class="tooltips"></div>
    <div id="map"></div>
    <section class="col-md-12 org-map-wrapper pull-left">
        <div class="width-940">
            <div class="col-sm-6 col-md-5 organisation-info">
                <div class="logo">
                    @if(getVal($organization, [0, 'logo'], null))
                        <a href="#" class="organisation-logo">
                            <img src="{{ getVal($organization, [0, 'logo_url']) }}" alt="{{ getVal($organization, [0, 'name']) }}" width="auto" height="68">
                        </a>
                    @endif
                </div>
                <div class="organisation-more-info">
                <span class="organisation-name">
                    <a href="#" title="{{ getVal($organization, [0, 'reporting_org', 0, 'narrative', 0, 'narrative'], '')}}">
                        {{ getVal($organization, [0, 'reporting_org', 0, 'narrative', 0, 'narrative'], '')}}
                    </a>
                </span>
                    @if(getVal($organization, [0, 'address'], null))
                        <address><i class="pull-left material-icons">room</i>{{getVal($organization, [0, 'address'])}}</address>
                    @endif
                    <a href="{{url('/who-is-using/'.getVal($organization, [0, 'org_slug'], ''))}}" class="see-all-activities">
                        <i class="pull-left material-icons">arrow_back</i>@lang('perfectViewer.see_all_activities')
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="col-md-12 activity-main-wrapper">
        <div class="width-940">
            <div class="col-xs-12 activity-detail-wrapper">
                <div class="activity-detail-top-wrapper">
                    <h1>
                        {{ getVal($activity, [0, 'published_data', 'title', 0, 'narrative'], '') }}
                    </h1>
                    <div class="activity-iati-info">
                        <div class="pull-left iati-identifier-wrapper">@lang('perfectViewer.iati_identifier'):
                            <span class="iati-identifier">
                                {{ getVal($activity, [0, 'published_data', 'identifier', 'iati_identifier_text'], '') }}
                                    </span>
                        </div>
                        <div class="pull-right activity-publish-state">
                            {{-- @if(getVal($activity, [0, 'activity_in_registry'], null)) --}}
                            @if($activityPublishedStatus == 'Linked')
                                <span class="pull-left published-in-iati">
                                        @lang('perfectViewer.published_in_iati')
                                    </span>
                            @else
                                <span class="pull-left unpublished-in-iati">
                                        @lang('perfectViewer.not_published_in_iati')
                                    </span>
                            @endif
                            <img src="{{asset('images/ic-iati-logo.png')}}" alt="IATI" width="27" height="25">
                        </div>
                    </div>
                    <div class="activity-info activity-more-info">
                        <ul class="pull-left">
                            @if(getVal($activity, [0, 'published_data', 'activity_date']))
                                <li><i class="pull-left material-icons">date_range</i>
                                    @foreach(getVal($activity, [0, 'published_data', 'activity_date'], []) as $index => $date)
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
                                    @foreach(getVal($activity, [0, 'published_data', 'activity_date'], []) as $index => $date)
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
                            <li>
                                @if(getVal($activity, [0, 'published_data', 'activity_status'], null))
                                    <i class="pull-left material-icons">@lang('perfectViewer.autorenew')</i>
                                    <span>
                                        {{ $codeListHelper->getCodeNameOnly('ActivityStatus', getVal($activity, [0, 'published_data', 'activity_status'], '')) }}
                                        <i>(@lang('perfectViewer.status'))</i>
                                    </span>
                                @endif
                            </li>
                        </ul>
                        <ul class="pull-right links">
                            @if ($user)
                                <li><a href="mailto:{{$user->email}}"><i class="pull-left material-icons">mail</i>@lang('perfectViewer.contact')</a></li>
                            @endif
                            <li>
                                <a href="#"><i class="pull-left material-icons">share</i>@lang('perfectViewer.share')</a>
                                <ul class="share-links">
                                    <li class="facebook-share"><a href="javascript:shareThisPage()" target="_blank" alt="Share on Facebook">Facebook</a></li>
                                    <li class="twitter-share"><a id="twitter-button" href="javascript:void(0)">Tweet</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="activity-description">
                        <p>
                            {{getVal($activity, [0, 'published_data', 'description'], '')}}
                        </p>
                        @if(getVal($activity, [0, 'published_data', 'description'], '') != '')
                            <span class="show-more"><i class="material-icons">more_horiz</i></span>
                        @endif

                    </div>
                    <div class="activity-sectors">
                        @if(getVal($activity, [0, 'published_data', 'sector'], null))
                            @if (checkAllVocabularies(getVal($activity, [0, 'published_data', 'sector'], [])))
                                <span class="pull-left">@lang('perfectViewer.sector'):</span>
                            @endif
                            <ul class="pull-left">
                                @foreach(getVal($activity, [0, 'published_data', 'sector'], []) as $index => $sector)
                                    @if (!hasEmptySectorVocabulary($sector))
                                        <li>
                                            {{ getSectorName($sector) }}
                                            <i class="pull-right material-icons">error</i>
                                            <div class="sector-more-info">
                                                <dl>
                                                    <div class="sector-list">
                                                        <dt class="pull-left">@lang('perfectViewer.sector_code'):</dt>
                                                        <dd class="pull-left">{{ getSectorCode($sector) }}
                                                            - {{ getSectorName($sector) }} </dd>
                                                    </div>
                                                    <div class="sector-list">
                                                        <dt class="pull-left">@lang('perfectViewer.sector_vocabulary'):</dt>
                                                        <dd class="pull-left">{{getVal($sector, ['sector_vocabulary'], '')}}
                                                            - {{ $codeListHelper->getCodeNameOnly('SectorVocabulary', getVal($sector, ['sector_vocabulary'], '')) }}</dd>
                                                    </div>
                                                </dl>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="activity-block participating-organisation-block">
                    <h2>@lang('perfectViewer.participating_organisations')</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>@lang('global.organisation') @lang('global.name')</th>
                            <th>@lang('global.organisation') @lang('global.type')</th>
                            <th>@lang('global.organisation') @lang('global.role')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(getVal($activity, [0, 'published_data', 'participating_organization'], []) as $index => $org)
                            <tr>
                                <td>{{ getVal($org, ['narrative', 0, 'narrative'], @trans('perfectViewer.not_available')) }}</td>
                                <td>{{ $codeListHelper->getCodeNameOnly('OrganisationType', getVal($org, ['organization_type'], @trans('perfectViewer.not_available'))) }}</td>
                                <td>{{ $codeListHelper->getCodeNameOnly('OrganisationRole', getVal($org, ['organization_role'], @trans('perfectViewer.not_available'))) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="activity-block transaction-block">
                    <h2>@lang('global.transaction')</h2>
                    <table>
                        <thead>
                        <tr>
                            <th width="30%">@lang('global.transaction_value')</th>
                            <th width="30%">@lang('perfectViewer.provider') <img src="/images/ic-provider-receiver.svg" alt="" width="28" height="8"> @lang('perfectViewer.receiver')</th>
                            <th width="20%">@lang('global.type')</th>
                            <th width="20%">@lang('global.date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr>
                                <td>
                                    <span class="transaction-value">
                                        {{ getVal($transaction, ['transaction', 'value', 0, 'amount'], null) ? number_format(getVal($transaction, ['transaction', 'value', 0, 'amount'], 0)) : @trans('perfectViewer.not_available') }}
                                    </span>
                                    @if(getVal($transaction, ['transaction', 'value', 0, 'amount'], null))
                                        @if(getVal($transaction, ['transaction', 'value', 0, 'currency'], null))
                                            {{getVal($transaction, ['transaction', 'value', 0, 'currency'], @trans('perfectViewer.not_available'))}}
                                        @else
                                            {{getVal($defaultFieldValues, [0, 'default_currency'], '')}}
                                        @endif
                                        @if(getVal($transaction, ['transaction', 'value', 0, 'date'], null))
                                            <i>
                                                (@lang('perfectViewer.valued_at') {{dateFormat('M d, Y', getVal($transaction, ['transaction', 'value', 0, 'date'], ''))}})
                                            </i>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <span class="provider"><i>circle</i>
                                        {{getVal($transaction, ['transaction', 'provider_organization', 0, 'narrative', 0, 'narrative'], @trans('perfectViewer.provider'). ' N/A')}}
                                    </span>
                                    <span class="receiver"><i>circle</i>
                                        {{getVal($transaction, ['transaction', 'receiver_organization', 0, 'narrative', 0, 'narrative'],  @trans('perfectViewer.receiver'). ' N/A')}}
                                    </span>
                                </td>
                                <td class="type">
                                    <strong>{{ $codeListHelper->getCodeNameOnly('TransactionType', getVal($transaction, ['transaction', 'transaction_type', 0, 'transaction_type_code'], @trans('perfectViewer.not_available'))) }}</strong>
                                </td>
                                <td class="date"><i
                                            class="pull-left material-icons">date_range</i>{{dateFormat('M d, Y', getVal($transaction, ['transaction', 'transaction_date', 0, 'date']))}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="activity-block budget-block">
                    <h2>@lang('perfectViewer.budget')</h2>
                    <div class="budget-content">
                        <div class="pull-left total-budget">
                            <strong>
                                {{ number_format(round(getVal($activity, [0, 'published_data', 'totalBudget', 'value'], 0), 2)) }}
                            </strong>
                            <span class="currency">
                                USD
                            </span>
                            <label>@lang('perfectViewer.total_budget')</label>
                        </div>
                        <div class="pull-left budget-table">
                            <table>
                                <tbody>
                                @foreach(getVal($activity, [0, 'published_data', 'budget'], []) as $index => $budget)
                                    <tr>
                                        <td>
                                            <span class="transaction-value">
                                                {{number_format(getVal($budget, ['value', 0, 'amount'], 0))}}
                                                @if(getVal($budget, ['value', 0, 'amount'], null))
                                                    @if(getVal($budget, ['value', 0, 'currency'], null))
                                                        {{getVal($budget, ['value', 0, 'currency'], '')}}
                                                    @else
                                                        {{getVal($defaultFieldValues, [0, 'default_currency'], '')}}
                                                    @endif
                                                </span>
                                            <i>
                                                (@lang('perfectViewer.valued_at') {{dateFormat('M d, Y', getVal($budget, ['value', 0, 'value_date'], ''))}})
                                            </i>
                                            @endif

                                        </td>
                                        <td class="date"><i class="pull-left material-icons">date_range</i>
                                            {{dateFormat('M d, Y', getVal($budget, ['period_start', 0, 'date'], ''))}}
                                            -
                                            {{dateFormat('M d, Y', getVal($budget, ['period_end', 0, 'date'], ''))}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="activity-other-info">
                @if(getVal($activity, [0, 'updated_at'], null))
                    <div class="pull-left updated-date">
                        <i class="pull-left material-icons">access_time</i>@lang('perfectViewer.updated_on')
                        <span>
                            {{dateFormat('M d, Y H:i:s', getVal($activity, [0, 'updated_at'], ''))}}
                    </span>
                    </div>
                @endif
                @if(fileExists($activity))
                    <a href="{{'/files/xml/'.getVal($activity, [0, 'filename'], '#')}}" target="_blank" class="view-xml-file">@lang('perfectViewer.view_xml_file_here')</a>
                @endif
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
<script>
    var recipientCountries = {!!json_encode(array_flip($recipientCountries))!!};

    var pathColorCode = "#D9E5EB";
    var recipientCountryColorCode = "#00A8FF";

    @if(isTzSubDomain())
        pathColorCode = "#DAEBDE";
        recipientCountryColorCode = "#1AAB3C";
    @endif
</script>
<script src="/js/jquery.js"></script>
<script src="/js/d3.min.js"></script>
<script type="text/javascript" src="/js/worldMap.js"></script>
<script type="text/javascript" src="/js/publicPages.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        if ($('.activity-description p').height() < 64) {
            $('.show-more').hide();
        }

        $('.activity-description .show-more').click(function () {
            $(this).siblings('p').animate({
                'max-height': '5000px',
                'height': '100%'
            });
            $(this).hide();
        });
    });
</script>
<script language="javascript">
    var shareThisPage = function () {
        var url = "{!! urlencode(url()->current()) !!}";

        window.open("https://www.facebook.com/sharer/sharer.php?u=" + url + "&t=" + document.title, '',
            'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
        return false;
    };

    var tweetUrl = 'https://twitter.com/intent/tweet?url=' + "{!! (url()->current()) !!}";

    $('#twitter-button').click(function () {
        $(this).attr('href', tweetUrl);
    });

</script>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</body>
</html>
