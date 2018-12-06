<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="/images/favicon.png"/>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.min.css">
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
@if($isTz)
	@include('tz.partials.header')
@elseif($isNp)
	<section class="header-banner">
		@include('np.partials.header')
	</section>
@else
	@include('includes.header')
@endif

<section class="organisation-detail-container">
    <div class="contact-section clearfix">
        @if($orgInfo['logo'])
            <div class="logo">
                <img src="{{ $orgInfo['logo_url'] }}">
            </div>
        @endif
        <div class="detail">
            <h2>{{$orgInfo['name']}}</h2>

            <div>
                <div class="social-logo">
                    @if($user->email)
                        <span><a href="mailto:{{$user->email}}" class="mail">{{$user->email}}</a></span>
                    @endif
                    @if($orgInfo['organization_url'])
                        <span><a href="{{$orgInfo['organization_url']}}"
                                 class="web">{{$orgInfo['organization_url']}}</a></span>
                    @endif
                    @if($orgInfo['twitter'])
                        <span><a href="http://www.twitter.com/{{$orgInfo['twitter']}}" target="_blank"
                                 class="twitter">{{$orgInfo['twitter']}}</a></span>
                    @endif
                </div>
                @if($orgInfo['address'])
                    <span class="address">{{$orgInfo['address']}}</span>
                @endif
                @if($orgInfo['telephone'])
                    <span class="tel"><a href="tel:{{$orgInfo['telephone']}}">{{$orgInfo['telephone']}}</a></span>
                @endif
            </div>
        </div>
    </div>
    <div class="activities-detail-section">
        <div>
            <div class="content-1 clearfix">
                <div class="content-item">
                    <span>Total Commitments</span>

                    <p>${{$final_data['transaction']['commitment']}}</p>
                </div>
                <div class="content-item">
                    <span>Total Disbursements</span>

                    <p>${{$final_data['transaction']['disbursement']}}</p>
                </div>
                <div class="content-item">
                    <span>Total Expenditures</span>

                    <p>${{$final_data['transaction']['expenditure']}}</p>
                </div>
                <div class="content-item">
                    <span>Total Incoming Funds</span>

                    <p>${{$final_data['transaction']['incomingFunds']}}</p>
                </div>
            </div>
            @if(!empty($final_data['activity_name']))
                <div class="content-2 clearfix">
                    <div class="content-item">
                        <h2>Activities By Status</h2>

                        <div id="horizontalBarChart"></div>
                    </div>
                    <div class="content-item">
                        <div>
                            <?php $title = $final_data['activity_name']['title'];
                            $identifier = $final_data['activity_name']['identifier'];
                            ?>
                            <h2> Activities ({{count($identifier)}}) </h2>

                            <div class="content-activity">
                                @foreach($title as $index => $value)
                                    <div class="info">
                                        <span>{{ $identifier[$index] }}</span>

                                        <p>{{ $title[$index] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="content-3">
                <div class="content-item">
                    <h2>Recipient Country</h2>

                    <div id="map"></div>
                </div>
            </div>
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
                    <li><a href="https://twitter.com/aidstream" class="twitter" title="Follow us on Twitter">Follow us
                            on Twitter</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-nav bottom-line">
            <div class="col-md-12">
                <ul>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li><a href="{{ url('/who-is-using') }}">Who's using</a></li>
                    <!--<li><a href="#">Snapshot</a></li>-->
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


<script>
    var finalData = {!!json_encode($final_data)!!};
</script>

<script src="/js/d3.min.js"></script>
<script src="js/jquery.js"></script>
<script src="/js/horizontalBar.js"></script>
<script type="text/javascript" src="/js/worldMap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".content-activity").jScrollPane();
    });
</script>
</body>
</html>

