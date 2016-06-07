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
    <link rel="shotcut icon" type="image/png" sizes="32*32" href="{{ asset('/images/favicon.png') }}"/>
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
            <div class="light-text">Last updated on: {{ formatDate($project['updated_at'], 'Y/m/d') }}</div>
        </div>
    </div>

    <div class="container container--shadow container--shadow--buffer-top small-container">
        <div class="row">
            <div class="col-md-12 intro-section clearfix">
                <div class="col-md-3 col-sm-4 col-xs-4 vertical-horizontal-center-wrap">
                    <div class="vertical-horizontal-centerize">
                        @if($orgDetail->logo)
                            <div class="organization-logo"><img src={{ $orgDetail->logo_url }} ></div>
                            <div class="organization-name"><a href="{{ route('project.public', $orgDetail->id) }}">{{$orgDetail->name}}</a></div>
                        @else
                            <div class="organization-name"><a href="{{ route('project.public', $orgDetail->id) }}">{{$orgDetail->name}}</a></div>
                        @endif
                    </div>
                </div>

                <div class="col-md-9 col-sm-8 col-xs-8 right-map-section"">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 name-value-section">
            @foreach($project->description as $description)
                @if(getVal($description, ['type']) == 1)
                    <dl class="clearfix">
                        <dt class="col-md-3 col-sm-4 col-xs-4">General description</dt>
                        <dd class="col-md-9 col-sm-8 col-xs-8">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif

                @if(getVal($description, ['type']) == 2)
                    <dl class="clearfix">
                        <dt class="col-md-3 col-sm-4 col-xs-4">Objectives</dt>
                        <dd class="col-md-9 col-sm-8 col-xs-8">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif

                @if(getVal($description, ['type']) == 3)
                    <dl class="clearfix">
                        <dt class="col-md-3 col-sm-4 col-xs-4">Target Groups</dt>
                        <dd class="col-md-9 col-sm-8 col-xs-8">
                            {{$description['narrative'][0]['narrative']}}
                        </dd>
                    </dl>
                @endif
            @endforeach


            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">Project Status</dt>
                <dd class="col-md-9 col-sm-8 col-xs-8">{{ $getCode->getCodeListName('Activity','ActivityStatus', $project->activity_status) }}</dd>
            </dl>

            @foreach($project->activity_date as $date)
                @if($date['type'] == 2)
                    <dl class="clearfix">
                        <dt class="col-md-3 col-sm-4 col-xs-4">Start Date</dt>
                        <dd class="col-md-9 col-sm-8 col-xs-8">{{ formatDate($date['date'], 'Y/m/d') }}</dd>
                    </dl>
                @endif
                @if($date['type'] == 4)
                    <dl class="clearfix">
                        <dt class="col-md-3 col-sm-4 col-xs-4">End Date</dt>
                        <dd class="col-md-9 col-sm-8 col-xs-8">{{ formatDate($date['date'], 'Y/m/d') }}</dd>
                    </dl>
                @endif
            @endforeach

            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">Project Country</dt>
                <dd class="col-md-9 col-sm-8 col-xs-8">{{$getCode->getCodeListName('Organization','Country', $project->recipient_country[0]['country_code'])}}</dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">Location</dt>
                <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                    @if($project->location != null)
                        @foreach ($project->location as $location)
                            @foreach (getVal($location, ['administrative'], []) as $value)
                                @if ($value['level'] == 1 && $value['code'] != "")
                                    <div>
                                        {{ $value['code'] }}
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">
                    Results/Outcomes Documents
                </dt>
                <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                    @foreach($documentLinks as $documentLink)
                        @foreach($documentLink as $index => $data)
                            @if($data['url'] != "" && getVal($data, ['category', 0, 'code']) == "A08")
                                <a href="{{$data['url']}}" target="_blank">{{$data['url']}}</a>
                            @endif
                        @endforeach
                    @endforeach
                </dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">
                    Annual Reports
                </dt>
                <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                    @foreach($documentLinks as $documentLink)
                        @foreach($documentLink as $index => $data)
                            @if($data['url'] != "" && getVal($data, ['category', 0, 'code']) == "B01")
                                <a href="{{$data['url']}}" target="_blank">{{$data['url']}}</a>
                            @endif
                        @endforeach
                    @endforeach
                </dd>
            </dl>

            <dl class="clearfix">
                <dt class="col-md-3 col-sm-4 col-xs-4">
                    Budget
                </dt>
                <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                    @if ($project->budget)
                        @foreach($project->budget as $budget)
                            <div>
                                {{ number_format(getVal($budget, ['value', 0, 'amount'])) }} {{ getVal($budget, ['value', 0, 'currency']) }} &nbsp; {{ formatDate(getVal($budget, ['period_start', 0, 'date']), 'Y/m/d') }} - {{ formatDate(getVal($budget, ['period_end', 0, 'date']), 'Y/m/d') }}
                            </div>
                        @endforeach
                    @endif
                </dd>
            </dl>

            @if(!empty($fundings))
                <dl class="clearfix">
                    <dt class="col-md-3 col-sm-4 col-xs-4">Funding Organisation</dt>
                    <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                        @foreach($fundings as $funding)
                            @if($funding['narrative'][0]['narrative'] != "")
                                <div>
                                    {{$funding['narrative'][0]['narrative']}} , <span>{{ $getCode->getCodeListName('Activity','OrganisationType', $funding['organization_type']) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </dd>
                </dl>
            @endif

            @if(!empty($implementings))
                <dl class="clearfix">
                    <dt class="col-md-3 col-sm-4 col-xs-4">Implementing Organisation</dt>
                    <dd class="col-md-9 col-sm-8 col-xs-8 list-wrap">
                        @foreach($implementings as $implementing)
                            @if($implementing['narrative'][0]['narrative'] != "")
                                <div>{{$implementing['narrative'][0]['narrative']}} ,
                                    <span>{{ $getCode->getCodeListName('Activity','OrganisationType', $implementing['organization_type']) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </dd>
                </dl>
            @endif
        </div>

        @if(!empty($disbursements))
            <div class="col-md-12 name-value-section">
                <div class="title">Disbursement</div>
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
                            <td>{{ number_format($disbursement['value'][0]['amount']) }} {{ $disbursement['value'][0]['currency'] }}</td>
                            <td>{{ $disbursement['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif


        @if(!empty($expenditures))
            <div class="col-md-12 name-value-section">
                <div class="title">Expenditure</div>
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
                            <td>{{ number_format($expenditure['value'][0]['amount']) }} {{ $expenditure['value'][0]['currency'] }}</td>
                            <td>{{ $expenditure['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(!empty($incomingFunds))
            <div class="col-md-12 name-value-section">

                <div class="title">Incoming Funds</div>
                <table class="table table-striped custom-table" id="data-table">
                    <thead>
                    <tr>
                        <th width="40%">Date</th>
                        <th class="">Amount</th>
                        <th class="">Provider</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($incomingFunds as $incomingFund)
                        <tr>
                            <td>{{ formatDate($incomingFund['transaction_date'][0]['date']) }}</td>
                            <td>{{ number_format($incomingFund['value'][0]['amount']) }} {{ $incomingFund['value'][0]['currency'] }}</td>
                            <td>{{ $incomingFund['provider_organization'][0]['narrative'][0]['narrative'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>

@include('tz.partials.footer')
<script src="{{url('/js/jquery.js')}}"></script>
<script src="{{url('/js/modernizr.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/leaflet/leaflet.js')}}"></script>
<script>
    function GetMap() {
        var map = L.map(document.getElementById("map")).setView([-6.369028, 31.988822], 5);
        map.scrollWheelZoom.disable();
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a>; contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(map);
        return map;
    }
    function ShowProjectInMap(regionname, map) {
        var tz_regions_center = {
            "Dodoma": [-5.775, 35.955],
            "Arusha": [-2.925, 36.11],
            "Kilimanjaro": [-3.745, 37.650000000000006],
            "Tanga": [-5.12, 38.144999999999996],
            "Morogoro": [-7.880000000000001, 36.92],
            "Pwani": [-7.175, 38.849999999999994],
            "Dar es Salaam": [-6.875, 39.28],
            "Lindi": [-9.38, 38.42],
            "Mtwara": [-10.77, 39.22],
            "Ruvuma": [-10.469999999999999, 36.235],
            "Iringa": [-8.72, 35.385],
            "Mbeya": [-8.290000000000001, 33.535],
            "Singida": [-5.65, 34.415],
            "Tabora": [-5.495, 32.665],
            "Rukwa": [-7.135, 31.37],
            "Kigoma": [-4.8100000000000005, 30.409999999999997],
            "Shinyanga": [-3.285, 33.205],
            "Kagera": [-2.21, 31.575000000000003],
            "Mwanza": [-2.45, 32.855000000000004],
            "Mara": [-1.75, 34.045],
            "Manyara": [-4.725, 36.455],
            "kaskazini": [-5.88, 39.29],
            "Kusini": [-6.25, 39.425],
            "Mjini Magharibi": [-6.205, 39.275],
            "Kaskazini Pemba": [-5.035, 39.755],
            "Kusini Pemba": [-5.32, 39.705]
        };

        if(tz_regions_center[regionname]) {
            map.setView(tz_regions_center[regionname], 5);
            L.marker(tz_regions_center[regionname]).addTo(map).bindPopup("Project in " + regionname);
        }
    }
    var map = GetMap();
</script>

@if($project->location != null)
    @foreach ($project->location as $location)
        @foreach (getVal($location, ['administrative'], []) as $value)
            @if ($value['level'] == 1 && $value['code'] != "")
                <script>ShowProjectInMap("{{ $value['code'] }}", map);</script>
            @endif
        @endforeach
    @endforeach
@endif
</body>

