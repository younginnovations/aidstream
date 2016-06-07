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
<body class="front-page">
<div class="header-banner">
    @include('tz.partials.header')
    <div class="introduction-wrapper bottom-line">
        <div class="container">
        <div class="col-md-12 text-center">
            <h1> A platform to display and explore civil society activities across Tanzania. </h1>

            <h2>

                We empower civil society organizations in Tanzania to be more transparent and achieve greater impact by using <a href="http://iatistandard.org/">IATI</a> in their local context.
            </h2>
            {{--<a href="{{ url('/auth/register') }}" class="btn btn-primary get-started-btn">Get Started</a>--}}
        </div>
        </div>
    </div>
</div>

<section class="main-container">
    <div id="container" class="map-section">
        <button id="reset">Reset</button>

        <div id="map"></div>

    {{--  select blocks over the map--}}
    <div class="select-block-wrap">
        <div class="container">
            <div class="col-md-12 select-cards-wrap">

                <h3>Use the filters below to start exploring.</h3>

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
    {{-- end of select blocks over the map--}}

    </div>

    <div class="container" id="projects-container">
    <div class="col-md-12">
        <div class="search-wrap">
            <input type="text" id="projects-search" placeholder="Search for a project...">
        </div>
        <table class="table table-striped custom-table project-data-table" id="data-table">
            <thead>
                <tr>
                    <th width="40%">Project Title</th>
                    <th class="">Reporting Organisation</th>
                    <th class="">Sectors</th>
                </tr>
            </thead>

            <tbody>

            </tbody>

        </table>
    </div>
</section>


@include('tz.partials.footer')
<script src="js/jquery.js"></script>
<script src="js/modernizr.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{url('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/js/tz/underscore-min.js')}}" ></script>
<script type="text/javascript" src="{{url('/js/tz/backbone-min.js')}}" ></script>
<script type="text/javascript" src="{{url('/js/tz/regions.js')}}" ></script>
<script type="text/javascript" src="{{url('/js/tz/leaflet/leaflet.js')}}" ></script>
<script type="text/javascript" src="{{url('/js/tz/mapping.js')}}" ></script>

<script>
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }
        hamburgerMenu();

        var projectCollection = new ProjectCollection({
            url: '/api/projects',
        });
        projectCollection.fetch({reset: true});
        var mapView = null;

        projectCollection.on('reset', function() {
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
            // grid.collection = projectCollection.filterProjects();
            // $("#projectslist").html(grid.render().el);
            $(".card-body").jScrollPane();

        });

    });
</script>

{{-- ---------- section for templates -------- --}}

<script type="text/template" id="project-list-item">
    <td class="bold-col"><a href="/public/project/<%= project['id'] %>"><%= project["title"] %></a></td>
    <td class="bold-col"><%= project["reporting_organisation"] %></td>
    <td class="bold-col"><%= project["sectors"] %></td>
</script>
<script type="text/template" id="region-checkbox-item">
  <label>
  <input type='checkbox' class='region-checkbox' /><%= region %>
  </label>
</script>
<script type="text/template" id="sector-checkbox-item">
  <label>
  <input type='checkbox' class='sector-checkbox' /><%= sector %>
  </label>
</script>

</body>
</html>

