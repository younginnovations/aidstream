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
@include('tz.partials.header')

<section class="main-container">
    <div class="container">
        <div class="title-section">
            <h1>{{$orgDetails->name}}</h1>
            <div class="location"><span>{{$orgDetails->address}}</span></div>
            <div class="social-logo">
                @if($user->email)
                    <span><a href="mailto:{{$user->email}}" class="mail">{{$user->email}}</a></span>
                @endif
                @if($orgDetails->organization_url)
                    <span><a href="{{$orgDetails->organization_url}}" class="web">{{$orgDetails->organization_url}}</a></span>
                @endif
                @if($orgDetails->twitter)
                    <span><a href="http://www.twitter.com/{{$orgDetails->twitter}}" target="_blank"
                             class="twitter">{{$orgDetails->twitter}}</a></span>
                @endif
            </div>
        </div>
    </div> {{--  container close--}}

    <div class="container small-container">
        <div class="col-md-12">
            <div class="row">
                <div class="header-name-value name-value-section clearfix">
                    <dl class="col-md-3 col-sm-6 col-xs-6">
                        <dt> Total Budget</dt>
                        <dd class="amount">${{ $totalBudget }}</dd>
                    </dl>
                    <dl class="col-md-3 col-sm-6 col-xs-6">
                        <dt> Total Disbursements</dt>
                        <dd class="amount">${{ number_format($transactionCount['disbursement']) }}</dd>
                    </dl>
                    <dl class="col-md-3 col-sm-6 col-xs-6">
                        <dt>Total Expenditures</dt>
                        <dd class="amount">${{ number_format($transactionCount['expenditure']) }}</dd>
                    </dl>
                    <dl class="col-md-3 col-sm-6 col-xs-6">
                        <dt> Total Incoming Funds</dt>
                        <dd class="amount">${{ number_format($transactionCount['incoming_fund']) }}</dd>
                    </dl>
                    <dl class="col-md-3 col-sm-6 col-xs-6">
                        <dt> Total Budget</dt>
                        <dd class="amount">${{ number_format($totalBudget) }}</dd>
                    </dl>
                </div>
            </div> {{--  row close--}}
        </div>
    </div>

    <div class="container container--shadow small-container">
        <div id="container" class="map-section detail-page-map">
            <button id="reset">Reset</button>

            <div id="map"></div>

            {{--  select blocks over the map--}}
            <div class="select-block-wrap">
                <div class="container">
                    <div class="col-md-12 select-cards-wrap">
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

        <div id="projects-container">
            <div class="col-md-12">
                <table class="table table-striped custom-table project-data-table" id="data-table">
                    <thead>
                    <tr>
                        <th width="60%">Project Title</th>
                        <th class="">Sectors</th>
                    </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>
            </div>
        </div> {{--  container close--}}
    </div>
</section>
@include('tz.partials.footer')
<script>
    var organizationId = "{{ request()->segment(3) }}";
</script>

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
            url: '/api/projects/' + organizationId,
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
            // grid.collection = projectCollection.filterProjects();
            // $("#projectslist").html(grid.render().el);
            $(".card-body").jScrollPane();

        });

    });
</script>

{{-- ---------- section for templates -------- --}}

<script type="text/template" id="project-list-item">
    <td class="bold-col"><a href="/public/project/<%= project['id'] %>"><%= project["title"] %></a></td>
    <td><%= project["sectors"] %></td>
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

