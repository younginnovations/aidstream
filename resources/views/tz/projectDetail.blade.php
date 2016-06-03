<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    {{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
    {{ header("Pragma: no-cache") }}
    {{ header("Expires: 0 ")}}
    <title>Aidstream</title>
    <link rel="shortcut icon" type="image/png" sizes="16*16" href="images/favicon.png"/>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.min.css') }}">
    <link href="{{ asset('/js/tz/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tanzania_style/tz.style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
</head>

<body>
@inject('getCode', 'App\Helpers\GetCodeName')

@include('tz.partials.header')

<section class="main-container">
    <div class="container">
        <div class="title-section">
            <h1>{{$project->title[0]['narrative']}}</h1>
            <div>{{$project->identifier['activity_identifier']}}</div>
            <div class="light-text">Last updated on: {{ formatDate($project['updated_at']) }}</div>
        </div>
    </div>

    <div class="container container--shadow">
        <div class="col-md-12 intro-section clearfix">
            <div class="col-md-3 vertical-horizontal-center-wrap">
                <div class="vertical-horizontal-centerize">
                    @if($orgDetail->logo)
                        <div class="organization-logo"><img src={{$orgDetail->logo}} width="106px" height="100px"></div>
                    @endif
                    <div class="organization-name">{{$orgDetail->name}}</div>
                </div>
            </div>

            <div class="col-md-9" style="height: 277px;"></div>
        </div>
        <div class="col-md-12 name-value-section">
            @foreach($project->description as $description)
                @if(getVal($description, ['type']) == 1)
                    <dl class="clearfix">
                        <dt class="col-md-3">General description</dt>
                        <dd class="col-md-9">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif

                @if(getVal($description, ['type']) == 2)
                    <dl class="clearfix">
                        <dt class="col-md-3">Objectives</dt>
                        <dd class="col-md-9">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif

                @if(getVal($description, ['type']) == 3)
                    <dl class="clearfix">
                        <dt class="col-md-3">Target Groups</dt>
                        <dd class="col-md-9">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif
            @endforeach


            <dl class="clearfix">
                <dt class="col-md-3">Project Status</dt>
                <dd class="col-md-9">{{ $getCode->getActivityCodeName('ActivityStatus', $project->activity_status) }}</dd>
            </dl>

            @foreach($project->activity_date as $date)
                @if($date['type'] == 2)
                    <dl class="clearfix">
                        <dt class="col-md-3">Start Date</dt>
                        <dd class="col-md-9">{{ $date['date']}}</dd>
                    </dl>
                @elseif($date['type'] == 4)
                    <dl class="clearfix">
                        <dt class="col-md-3">End Date</dt>
                        <dd class="col-md-9">{{ $date['date']}}</dd>
                    </dl>
                @endif
            @endforeach

            <dl class="clearfix">
                <dt class="col-md-3">Project Country</dt>
                <dd class="col-md-9">{{$getCode->getOrganizationCodeName('Country', $project->recipient_country[0]['country_code'])}}</dd>
            </dl>

            <dl class="clearfix">
                <dl class="clearfix">
                    <dt class="col-md-3">Location</dt>
                    <dd class="col-md-9 list-wrap">
                        @foreach ($project->location as $location)
                            @foreach (getVal($location, ['administrative'], []) as $value)
                                @if ($value['level'] == 1)
                                    <div>
                                        {{ $value['code'] }}
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </dd>
                </dl>
            </dl>

            @foreach($documentLinks as $documentLink)
                @foreach($documentLink as $index => $data)
                    <dl class="clearfix">
                        <dt class="col-md-3">
                            @if($index == 0)
                                Results/Outcomes Documents
                            @elseif($index == 1)
                                Annual Reports
                            @endif
                        </dt>
                        <dd class="col-md-9">
                            {{$data['url']}}
                        </dd>
                        @endforeach
                        @endforeach

                        @if(!empty($fundings))
                            <dl class="clearfix">
                                <dt class="col-md-3">Funding Organisation</dt>
                                <dd class="col-md-9 list-wrap">
                                    @foreach($fundings as $funding)
                                        <div>{{$funding['narrative'][0]['narrative']}} <span>{{ $getCode->getActivityCodeName('OrganisationType', $funding['organization_type']) }}</span></div>
                                    @endforeach
                                </dd>
                            </dl>
                        @endif

                        @if(!empty($implementings))
                            <dl class="clearfix">
                                <dt class="col-md-3">Implemeting Organisation</dt>
                                <dd class="col-md-9 list-wrap">
                                    @foreach($implementings as $implementing)
                                        <div>{{$implementing['narrative'][0]['narrative']}} <span>{{ $getCode->getActivityCodeName('OrganisationType', $implementing['organization_type']) }}</span>
                                        </div>
                                    @endforeach
                                </dd>
                            </dl>
                    @endif

                    {{--<dl class="clearfix">--}}
                    {{--<dt class="col-md-3">Sector</dt>--}}
                    {{--<dd class="col-md-9"><a href="#">i am a link</a></dd>--}}
                    {{--</dl>--}}
        </div>
        @if(!empty($disbursements))
            <div class="col-md-12">
                <div class="title">Disbursements</div>
                <table class="table table-striped custom-table" id="data-table">
                    <thead>
                    <tr>
                        <th width="40%">Date</th>
                        <th class="">Amount</th>
                        <th class="">Receiver</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($disbursements as $disbursement)
                        <tr>
                            <td>{{ formatDate($disbursement['transaction_date'][0]['date']) }}</td>
                            <td>{{ $disbursement['value'][0]['amount'] }}</td>
                            <td>{{ $disbursement['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif

                @if(!empty($expenditures))
                    <div class="title">Expenditures</div>
                    <table class="table table-striped custom-table" id="data-table">
                        <thead>
                        <tr>
                            <th width="40%">Date</th>
                            <th class="">Amount</th>
                            <th class="">Receiver</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($expenditures as $expenditure)
                            <tr>
                                <td>{{ formatDate($expenditure['transaction_date'][0]['date']) }}</td>
                                <td>{{ $expenditure['value'][0]['amount'] }}</td>
                                <td>{{ $expenditure['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if(!empty($incomingFunds))
                    <div class="title">Incoming Funds</div>
                    <table class="table table-striped custom-table" id="data-table">
                        <thead>
                        <tr>
                            <th width="40%">Date</th>
                            <th class="">Amount</th>
                            <th class="">Receiver</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($incomingFunds as $incomingFund)
                            <tr>
                                <td>{{ formatDate($incomingFund['transaction_date'][0]['date']) }}</td>
                                <td>{{ $incomingFund['value'][0]['amount'] }}</td>
                                <td>{{ $incomingFund['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
    </div>
</section>

@include('tz.partials.footer')
<script src="{{url('/js/jquery.js')}}"></script>
<script src="{{url('/js/modernizr.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/underscore-min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/backbone-min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/regions.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/leaflet/leaflet.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/mapping.js')}}"></script>
</body>

