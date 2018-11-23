<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    {{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
    {{ header("Pragma: no-cache") }}
    {{ header("Expires: 0 ")}}
    <title>Aidstream Nepal</title>
    <link rel="stylesheet" href="{{ asset('/css/vendor.min.css') }}">
    <link href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" rel="stylesheet">
    {!!  publicStylesheet() !!}
    <link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
</head>
<body class="front-page">
<div class="header-banner">
    @include('np.partials.header')
    <div class="introduction-wrapper">
        <div class="container">
            <div class="col-md-12 text-center">
                <h1> Effortlessly publishing <strong>Nepal's</strong> Aid data in <a href="http://iatistandard.org/">IATI format</a></h1>
                <p>AidStream Nepal is an online platform for Nepali organisations that wish to publish their aid data in accordance with the International Aid Transparency Initiative(IATI)
                    format but want to avoid dealing with the complexities of creating XML.</p>
                <a href="{{ url('/register') }}" class="btn btn-primary get-started-btn">Get Started</a>
                <p><a href="{{url('/who-is-using')}}">{{$organizationCount}} organizations</a> are using AidStream Nepal and have published <strong>{{$publishedActivitiesCount}} activities</strong> so far</p>
            </div>
        </div>
    </div>
    <div class="info-wrapper">
        <p>AidStream Nepal is a version of <a href="https://aidstream.org/">AidStream</a> customized to meet the needs of Nepali projects/ organizations. If you want to check out AidStream,
            <a href="https://aidstream.org/">go here.</a></p>
    </div>
</div>

<style>
    .sector-title {
        max-height: 224px;
        max-width: 250px;
        background: #fff;
    }
</style>

@inject('getCode', 'App\Helpers\GetCodeName')

<section class="main-container">
    <div id="container" class="map-section">
        <button id="reset">Reset</button>

        {{--<div id="tzmap" style="height: ; width: ;"></div>--}}
        <div id="tzmap"></div>
        {{--  select blocks over the map--}}
        <div class="select-block-wrap">
            <div class="container">
                <div class="col-md-12 width-900 select-cards-wrap">
                    <div class="sectors-block">
                        <div class="card small-card">
                            <div class="card-header title">SECTORS</div>
                            <div class="card-body jspScrollable">
                                <div id="sectors" class="checkbox checkbox-primary"></div>
                            </div>
                        </div>
                    </div>
                    <div class="sectors-block">
                        <div class="card small-card">
                            <div class="card-header title">Regions</div>
                            <div class="card-body jspScrollable">
                                <div id="regions" class="checkbox checkbox-primary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="projects-container">
        <div class="col-md-12 width-900">
            <div class="search-wrap">
                <input type="text" id="projects-search" placeholder="Search project title, sector or any other keyword ...">
            </div>
            <table class="table table-striped custom-table project-data-table" id="data-table">
                <thead>
                <tr>
                    <th width="35%">Project Title</th>
                    <th width="32%">Reporting Organisation</th>
                    <th width="33%">Sectors</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>


@include('np.partials.footer')
<script src="{{ asset('/np/js/jquery.js') }}"></script>
<script src="{{ asset('/np/js/modernizr.js') }}"></script>
<script type="text/javascript" src="{{ asset('/np/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/np/js/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/underscore-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/backbone-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/regions.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/leaflet/leaflet.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/mapping.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>

<script>
    var projectCollection = new ProjectCollection({
        url: '/api/activities'
    });

    projectCollection.fetch({reset: true});
    var mapView = null;

    projectCollection.on('reset', function () {
        new SectorsListView({
            collection: projectCollection.getSectorsCollection(),
            projectsCollection: projectCollection
        }).render();
        new RegionListView({
            collection: projectCollection.getRegionsCollection(),
            projectsCollection: projectCollection
        }).render();
        new ProjectsListView({
            collection: projectCollection
        });
        mapview = new MapView({collection: projectCollection})
        projectCollection.trigger('renderAll');

        $(".zanzibar").click(function (e) {
            projectCollection.resetSectors();
            projectCollection.resetRegions();
            projectCollection.trigger("zoom-zanzibar");
            projectCollection.trigger("select-zanzibar");
            projectCollection.trigger('renderAll');
        });
        $(".resetmap").click(function (e) {
            projectCollection.resetSectors();
            projectCollection.resetRegions();
            projectCollection.trigger('renderAll');
        });
        $(".card-body").jScrollPane();
    });
</script>

<script type="text/template" id="project-list-item">
    <td><a href="<%= project['activity_url'] %>"><%= project["title"] %></a></td>
<td><a href="<%= project['organization_url']%>"><%= project["reporting_organisation"] %></a></td>
<td><%= project["sectors"] %></td>
</script>
<script type="text/template" id="region-checkbox-item">
<label>
<input type='checkbox' class='region-checkbox'/><%= region %>
</label>
</script>
<script type="text/template" id="sector-checkbox-item">
<label>
<input type='checkbox' class='sector-checkbox' /><%= sector %>
</label>
</script>


<script>
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }

        hamburgerMenu();
    });
</script>

</body>
</html>

