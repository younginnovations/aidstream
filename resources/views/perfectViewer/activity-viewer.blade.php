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
    <meta name="twitter:title" content="Activity Viewer - {{ getVal($activity, [0, 'published_data', 'title', 0, 'narrative'], '') }}">
    <meta name="twitter:description" content="{{ getVal($activity, [0, 'published_data', 'description']) }}">
    <meta name="twitter:image:src" content="{{ url('images/aidstream_logo.png') }}"/>
    <meta name="twitter:url" content="{{ url()->current() }}"/>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="{{asset('/images/favicon.png')}}"/>
    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <link href="{{asset('/css/jquery.jscrollpane.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.min.css')}}">
    <title>Activity Viewer</title>
</head>
<body>
@include('includes.header')
<div class="wrapper">
    <div id="tooltip" class="tooltips"></div>
    <div id="map"></div>
    <section class="col-md-12 org-map-wrapper">
        <div class="width-940">
            <div class="col-md-4 organisation-info">
                <a href="#" class="organisation-logo">
                    <img src="{{ $organization[0]['logo_url'] }}" alt="{{ $organization[0]['name'] }}" width="auto" height="68">
                </a>
                <span class="organisation-name">
                    <a href="#" title="AbleChildAfrica">
                        {{ getVal($organization, [0, 'name'], '')}}
                    </a>
                </span>
                @if($organization[0]['address'])
                    <address><i class="pull-left material-icons">room</i>{{getVal($organization, [0, 'address'])}}</address>
                @endif
                <a href="{{url('/who-is-using/'.getVal($organization, [0, 'org_slug'], ''))}}" class="see-all-activities"><i class="pull-left material-icons">arrow_back</i>See all
                    Activities</a>
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
                        <div class="pull-left iati-identifier-wrapper">IATI Identifier:
                            <span class="iati-identifier">
                                {{ getVal($activity, [0, 'published_data', 'identifier', 'iati_identifier_text'], '') }}
                                    </span>
                        </div>
                        <div class="pull-right activity-publish-state">
                            @if(getVal($activity, [0, 'activity_in_registry'], null))
                                <span class="pull-left published-in-iati">
                                        Published in IATI
                                    </span>
                            @else
                                <span class="pull-left unpublished-in-iati">
                                        Not Published in IATI
                                    </span>
                            @endif
                            <img src="{{asset('images/ic-iati-logo.png')}}" alt="IATI" width="27" height="25">
                        </div>
                    </div>
                    <div class="activity-info activity-more-info">
                        <ul class="pull-left">
                            @if($activity[0]['published_data']['activity_date'])
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
                                    <i class="pull-left material-icons">autorenew</i>
                                    <span>
                                        {{ $codeListHelper->getCodeNameOnly('ActivityStatus', getVal($activity, [0, 'published_data', 'activity_status'], '')) }}
                                        <i>(Status)</i>
                                    </span>
                                @endif
                            </li>
                        </ul>
                        <ul class="pull-right links">
                            <li><a href="mailto:{{$user->email}}"><i class="pull-left material-icons">mail</i>Contact</a></li>
                            <li>
                                <a href="#"><i class="pull-left material-icons">share</i>Share</a>
                                <ul class="share-links">
                                    <li class="facebook-share"><a href="javascript:shareThisPage()" target="_blank" alt="Share on Facebook">Facebook</a></li>
                                    <li class="twitter-share"><a id="twitter-button" href="javascript:void(0)">Tweet</a></li>
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
                            <span class="pull-left">Sectors:</span>
                            <ul class="pull-left">
                                @foreach(getVal($activity, [0, 'published_data', 'sector'], []) as $index => $sector)
                                    <li>
                                        {{ getSectorName($sector) }}
                                        <i class="pull-right material-icons">error</i>
                                        <div class="sector-more-info">
                                            <dl>
                                                <div class="sector-list">
                                                    <dt class="pull-left">Sector code:</dt>
                                                    <dd class="pull-left">{{ getSectorCode($sector) }}
                                                        - {{ getSectorName($sector) }} </dd>
                                                </div>
                                                <div class="sector-list">
                                                    <dt class="pull-left">Sector vocabulary</dt>
                                                    <dd class="pull-left">{{getVal($sector, ['sector_vocabulary'], '')}}
                                                        - {{ $codeListHelper->getCodeNameOnly('SectorVocabulary', getVal($sector, ['sector_vocabulary'], '')) }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="activity-block participating-organisation-block">
                    <h2>Participating Organisations</h2>
                    <table>
                        <thead>
                        <tr>
                            <th>Organisation Name</th>
                            <th>Organisation Type</th>
                            <th>Organisation Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(getVal($activity, [0, 'published_data', 'participating_organization'], []) as $index => $org)
                            <tr>
                                <td>{{ getVal($org, ['narrative', 0, 'narrative'], 'Not Available') }}</td>
                                <td>{{ $codeListHelper->getCodeNameOnly('OrganisationType', getVal($org, ['organization_type'], 'Not Available')) }}</td>
                                <td>{{ $codeListHelper->getCodeNameOnly('OrganisationRole', getVal($org, ['organization_role'], 'Not Available')) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="activity-block transaction-block">
                    <h2>Transaction</h2>
                    <table>
                        <thead>
                        <tr>
                            <th width="30%">Transaction Value</th>
                            <th width="30%">Provider <img src="/images/ic-provider-receiver.png" alt="" width="28" height="8"> Receiver</th>
                            <th width="20%">Type</th>
                            <th width="20%">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr>
                                <td>
                                    <span class="transaction-value">
                                        {{getVal($transaction, ['transaction', 'value', 0, 'amount'], 'Not Available')}}
                                    </span>
                                    @if(getVal($transaction, ['transaction', 'value', 0, 'amount'], null))
                                        @if(getVal($transaction, ['transaction', 'value', 0, 'currency'], null))
                                            {{getVal($transaction, ['transaction', 'value', 0, 'currency'], 'Not Available')}}
                                        @else
                                            {{getVal($defaultFieldValues, [0, 'default_currency'], '')}}
                                        @endif
                                        @if(getVal($transaction, ['transaction', 'value', 0, 'date'], null))
                                            <i>
                                                (Valued at {{dateFormat('M d, Y', getVal($transaction, ['transaction', 'value', 0, 'date'], ''))}})
                                            </i>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <span class="provider"><i>circle</i>
                                        {{getVal($transaction, ['transaction', 'provider_organization', 0, 'narrative', 0, 'narrative'], 'Provider N/A')}}
                                    </span>
                                    <span class="receiver"><i>circle</i>
                                        {{getVal($transaction, ['transaction', 'receiver_organization', 0, 'narrative', 0, 'narrative'], 'Receiver N/A')}}
                                    </span>
                                </td>
                                <td class="type">
                                    <strong>{{ $codeListHelper->getCodeNameOnly('TransactionType', getVal($transaction, ['transaction', 'transaction_type', 0, 'transaction_type_code'], 'Not Available')) }}</strong>
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
                    <h2>Budget</h2>
                    <div class="budget-content">
                        <div class="pull-left total-budget">
                            <strong>
                                {{ number_format(round(getVal($activity, [0, 'published_data', 'totalBudget', 'value'], 0), 2)) }}
                            </strong>
                            <span class="currency">
                                USD
                            </span>
                            <label>Total Budget</label>
                        </div>
                        <div class="pull-left budget-table">
                            <table>
                                <tbody>
                                @foreach(getVal($activity, [0, 'published_data', 'budget'], []) as $index => $budget)
                                    <tr>
                                        <td>
                                            <span class="transaction-value">
                                                {{getVal($budget, ['value', 0, 'amount'], 0)}}
                                                @if(getVal($budget, ['value', 0, 'amount'], null))
                                                    @if(getVal($budget, ['value', 0, 'currency'], null))
                                                        {{getVal($budget, ['value', 0, 'currency'], '')}}
                                                    @else
                                                        {{getVal($defaultFieldValues, [0, 'default_currency'], '')}}
                                                    @endif
                                                </span>
                                            <i>
                                                (Valued at {{dateFormat('M d, Y', getVal($budget, ['value', 0, 'value_date'], ''))}})
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
                        <i class="pull-left material-icons">access_time</i>Updated on
                        <span>
                            {{dateFormat('M d, Y H:i:s', getVal($activity, [0, 'updated_at'], ''))}}
                    </span>
                    </div>
                @endif
                <a href="{{'/files/xml/'.getVal($activity, [0, 'filename'], '#')}}" target="_blank" class="view-xml-file">View XML file here</a>
            </div>
        </div>
    </section>
    <footer>
        <div class="width-900">
            <div class="social-wrapper bottom-line">
                <div class="col-md-12 text-center">
                    <ul>
                        <li><a href="https://github.com/younginnovations/aidstream-new" class="github"
                               title="Fork us on Github">Fork us on Github</a></li>
                        <li><a href="https://twitter.com/aidstream" class="twitter" title="Follow us on Twitter">Follow
                                us
                                on Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-nav bottom-line">
                <div class="col-md-12">
                    <ul>
                        <li><a href="{{ url('/about') }}">About</a></li>
                        <li><a href="{{ url('/who-is-using') }}">Who's using</a></li>
                    </ul>
                    <ul>
                        @if(auth()->check())
                            <li>
                                <a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? config('app.admin_dashboard') : config('app.super_admin_dashboard'))}}">Go
                                    to Dashboard</a>
                            </li>
                        @else
                            <li><a href="{{ url('/auth/login') }}">Login</a></li>
                            <li><a href="{{ url('/auth/register') }}">Register</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="footer-logo">
                <div class="col-md-12 text-center">
                    <a href="{{ url('/') }}"><img src="/images/logo-text.png" alt=""></a>
                </div>
            </div>
        </div>
        <div class="width-900 text-center">
            <div class="col-md-12 support-desc">
                For queries, suggestions, shoot us an email at <a href="mailto:support@aidstream.org">support@aidstream
                    .org</a>
            </div>
        </div>
    </footer>

</div>
<script>
    var recipientCountries = {!!json_encode(array_flip($recipientCountries))!!};
</script>
<script src="/js/jquery.js"></script>
<script src="/js/d3.min.js"></script>
<script type="text/javascript" src="/js/worldMap.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }

        hamburgerMenu();

        if ($('.activity-description p').height() < 64) {
            $('.show-more').hide();
        }

        $('.activity-description .show-more').click(function () {
            $(this).siblings('p').animate({
                'max-height': '1000px',
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
