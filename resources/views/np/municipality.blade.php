@extends('np.main')

@section('title', 'Municipality')

@section('links')
	<link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
@endsection

@section('content')
<div class="municipality-page">
<section class="header-banner">
	@include('np.partials.header')
	<div class="introduction-wrapper municipality">
		<div class="container">
			<div class="col-md-6">
				<div class="hero-content">
					<h1 class="lead">Dhangadhi Sub-Metropolitan City</h1>
					<p class="description">AidStream Dhangadhi is a tool developed to meet the transparency needs of CSOs, development partners, government and wider stakeholders in Dhangadhi</p>
					<a href="{{ url('/register') }}" class="btn btn-primary get-started-btn">Get Started</a>
				</div>
			</div>

			<div class="col-md-6 d-none">
				<div class="hero-image">
					<img src="{{asset('images/np/ic_less_complexities.svg')}}" alt="Banner Illustration">
				</div>
			</div>
		</div>
	</div>
</section>

<div class="section-grid">
	{{--Sector-chart--}}
	<section class="np-section" style="max-width: 60%; flex-basis: 60%;">
		<div class="section-card bg-grey">
			<div class="header">
				<h3>TOP 5 SECTORS</h3>
			</div>
			<div class="body">
				<div class="stats">
					<h1 class="number">{{ $sectorCount }}</h1>
					<span class="text">Total sectors</span>
				</div>
				<div class="bar-chat-wrapper">
					<div class="sector-chart-container" id="sector-chart">
						<svg width="460" height="320"></svg>
					</div>
				</div>
			</div>
		</div>
	</section>

	{{--Organization-chart--}}
	<section class="np-section" style="max-width: 40%; flex-basis: 40%;">
		<div class="section-card bg-grey">
			<div class="header">
				<h3>Organization</h3>
			</div>
			<div class="body">
				<div class="stats">
					<h1 class="number">{{ $organizationCount }}</h1>
					<span class="text">Total organizations</span>
				</div>
				<div class="bar-chat-wrapper">
					<div class="organization-chart-container" id="organization-chart">
						<svg width="250" height="320"></svg>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<section class="map-section">
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
				{{-- <div class="sectors-block">
					<div class="card small-card">
						<div class="card-header title">Regions</div>
						<div class="card-body jspScrollable">
							<div id="regions" class="checkbox checkbox-primary"></div>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	</div>
</section>

<section class="np-section bg-grey" id="projects-container">
	<div class="project-list-wrap">
		<div class="project-card">
			<div class="header">
				<h2>Projects</h2>
				<div class="search">
					<div class="search-input">
						<input type="text" id="projects-search" placeholder="Search"/>
					</div>
				</div>
			</div>
			<div class="body">
				<div class="table">
					<table id="data-table" class="project-data-table">
						<thead>
						<tr>
							<th>Title</th>
							<th>Reporting</th>
							<th>Sectors</th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
@endsection

@section('script')
<script src="{{ asset('/np/js/modernizr.js') }}"></script>
<script type="text/javascript" src="{{ asset('/np/js/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/underscore-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/backbone-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/regions.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/leaflet/leaflet.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/mapping.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://d3js.org/d3.v4.min.js"></script>

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

    //Sector-bar-chart
    // var sector_static_data = {!!$sectors!!};
    var sector_static_data =
    [{
        sector_area: 'Education',
        sector_count: 12
    }, 
    {
        sector_area: 'Health',
        sector_count: 40
    }, 
    {
        sector_area: 'Traning',
        sector_count: 80
    }, 
    {
        sector_area: 'Biodiversity',
        sector_count: 30
    }, 
    {
        sector_area: 'Preservation',
        sector_count: 120
    }];


    var tip = d3.select(".sector-chart-container")
        .append("div")
        .attr("class", "tip")
        .style("position", "absolute")
        .style("z-index", "10")
        .style("visibility", "hidden");

    var svg = d3.select("#sector-chart svg").attr("class", "background-style"),
        margin = {top: 20, right: 20, bottom: 42, left: 40},
        width = +svg.attr("width") - margin.left - margin.right,
        height = +svg.attr("height") - margin.top - margin.bottom;

    var x = d3.scaleBand().rangeRound([0, width]).padding(0.05),
        y = d3.scaleLinear().rangeRound([height, 0]);

    var g = svg.append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    d3.json("apiPlaceholderURL", function (error, data) {
        //if (error) throw error;

        data = sector_static_data;

        x.domain(data.map(function (d) {
            return d.sector_area;
        }));
        y.domain([0, d3.max(data, function (d) {
            return d.sector_count;
        })]);

        g.append("g")
            .attr("class", "axis axis--x")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x))
            .append("text")
            .attr("y", 6)
            .attr("dy", "2.5em")
            .attr("dx", width / 2 - margin.left)
            .attr("text-anchor", "start")

        g.selectAll(".bar")
            .data(data)
            .enter().append("rect")
            .attr("class", "bar")
            .attr("x", function (d) {
                return x(d.sector_area);
            })
            .attr("y", function (d) {
                return y(d.sector_count);
            })
            .attr("width", x.bandwidth())
            .attr("height", function (d) {
                return height - y(d.sector_count)
            })
            .on("mouseenter", function (d) {
                return tip.text(d.sector_count).style("visibility", "visible").style("top", y(d.sector_count) - 13 + 'px').style("left", x(d.sector_area) + x.bandwidth() - 12 + 'px')
            })
            //.on("mousemove", function(){return tooltip.style("top", (d3.event.pageY-10)+"px").style("left",(d3.event.pageX+10)+"px");})
            .on("mouseout", function () {
                return tip.style("visibility", "hidden");
            });
    });

    //Organization-bar-chart
    var organization_static_data = [
    {
        organization_area: 'Community',
        organization_count: 42
    }, 
    {
        organization_area: 'Local NGO',
        organization_count: 102
    }
    ];

    var tip_org = d3.select(".organization-chart-container")
        .append("div")
        .attr("class", "tip")
        .style("position", "absolute")
        .style("z-index", "10")
        .style("visibility", "hidden");

    var svg = d3.select("#organization-chart svg").attr("class", "background-style"),
        margin_org = {top: 20, right: 20, bottom: 42, left: 40},
        width_org = +svg.attr("width") - margin_org.left - margin_org.right,
        height_org = +svg.attr("height") - margin_org.top - margin_org.bottom;

    var x_org = d3.scaleBand().rangeRound([0, width_org]).padding(0.05),
        y_org = d3.scaleLinear().rangeRound([height_org, 0]);

    var g_org = svg.append("g")
        .attr("transform", "translate(" + margin_org.left + "," + margin_org.top + ")");

    d3.json("apiPlaceholderURL", function (error, data) {
        //if (error) throw error;

        data = organization_static_data;

        x_org.domain(data.map(function (d) {
            return d.organization_area;
        }));
        y_org.domain([0, d3.max(data, function (d) {
            return d.organization_count;
        })]);

        g_org.append("g")
            .attr("class", "axis axis--x")
            .attr("transform", "translate(0," + height_org + ")")
            .call(d3.axisBottom(x_org))
            .append("text")
            .attr("y", 6)
            .attr("dy", "2.5em")
            .attr("dx", width_org / 2 - margin_org.left)
            .attr("text-anchor", "start")

//        g.append("g")
//            .attr("class", "axis axis--y")
//            .call(d3.axisLeft(y).ticks(10))
//            .append("text")
//            .attr("transform", "rotate(-90)")
//            .attr("y", 6)
//            .attr("dy", "0.71em")
//            .attr("text-anchor", "end")
//            .text("Student Count");


        g_org.selectAll(".bar")
            .data(data)
            .enter().append("rect")
            .attr("class", "bar")
            .attr("x", function (d) {
                return x_org(d.organization_area);
            })
            .attr("y", function (d) {
                return y_org(d.organization_count);
            })
            .attr("width", x_org.bandwidth())
            .attr("height", function (d) {
                return height_org - y_org(d.organization_count)
            })
            .on("mouseenter", function (d) {
                return tip_org.text(d.organization_count).style("visibility", "visible").style("top", y_org(d.organization_count) - 13 + 'px').style("left", x_org(d.organization_area) + x_org.bandwidth() - 12 + 'px')
            })
            //.on("mousemove", function(){return tooltip.style("top", (d3.event.pageY-10)+"px").style("left",(d3.event.pageX+10)+"px");})
            .on("mouseout", function () {
                return tip_org.style("visibility", "hidden");
            });
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

@endsection
