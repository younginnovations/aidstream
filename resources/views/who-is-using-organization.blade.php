<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
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
    @include('includes.header')

    <section class="organisation-detail-container">
        <div class="contact-section clearfix">
            <div class="logo">
                <img src="{{$orgInfo['logo'] ? $orgInfo['logo_url'] : url('images/no-logo.png')}}">
            </div>
            <div class="detail">
                <h2>{{$orgInfo['name']}}</h2>
                <div>
                    <div class="social-logo">
                        @if($user->email)
                            <span><a href="mailto:{{$user->email}}" class="mail">{{$user->email}}</a></span>
                        @endif
                        @if($orgInfo['organization_url'])
                            <span><a href="{{$orgInfo['organization_url']}}" class="web">{{$orgInfo['organization_url']}}</a></span>
                        @endif
                        @if($orgInfo['twitter'])
                            <span><a href="http://www.twitter.com/{{$orgInfo['twitter']}}" target="_blank" class="twitter">{{$orgInfo['twitter']}}</a></span>
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
                                @foreach($title as $index => $value)
                                    <div class="info">
                                        <span>{{ $identifier[$index] }}</span>
                                        <p>{{ $title[$index] }}</p>
                                    </div>
                                @endforeach
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

    @include('includes.footer')

    <script>
        var finalData = {!! json_encode($final_data) !!};
    </script>
    <script src="/js/d3.min.js"></script>
    <script src="/js/horizontalBar.js"></script>
    <script type="text/javascript" src="/js/worldMap.js"></script>
</body>
</html>

