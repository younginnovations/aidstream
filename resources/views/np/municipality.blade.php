<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
	{{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
	{{ header("Pragma: no-cache") }}
	{{ header("Expires: 0 ")}}
	<title>Aidstream Nepal - Municipality</title>
	<link rel="stylesheet" href="{{ asset('/css/vendor.min.css') }}">
	<link href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" rel="stylesheet">
	{!!  publicStylesheet() !!}
	<link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
</head>
<body class="municipality-page">
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
					<div id="sector-bar-chart"></div>
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
					<div id="organization-bar-chart"></div>
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
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js"></script>

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
    var mySectorData = [],
        colors = ['#1695A3', '#ACF0F2'],
        width = 475,
        height = 200,
        padding = 30,
        outerPadding = .2,
        barPadding = .1;

    for (var i = 0; i < 5; i++) {
        mySectorData.push(Math.ceil(Math.random() * 100) + 5);
    }

    var xScale = d3.scale.ordinal()
        .domain(d3.range(0, mySectorData.length))
        .rangeBands([padding, width - padding], barPadding, outerPadding);

    var yScale = d3.scale.linear()
        .domain([d3.max(mySectorData), 0])
        .range([height - (padding * 2), 0]);

    var yAxisScale = d3.scale.linear()
        .domain([Math.round(d3.max(mySectorData)), 0])
        .range([0, height - ( padding * 2 )]);

    var xAxis = d3.svg.axis()
        .scale(xScale)
        .orient('bottom');

    var chart = d3.select('#sector-bar-chart')
        .style('background', '#fff')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

    var chartBars = chart.selectAll('rect').data(mySectorData)
        .enter().append('rect')
        .attr('class', 'chart-bar')
        .attr('width', function (d) {
            return xScale.rangeBand();
        })
        .attr('height', 0)
        .attr('fill', '#ff9786')
        .attr('x', function (d, i) {
            return xScale(i);
        })
        .attr('y', height - padding);

    var labelSVG = chart.selectAll('svg').data(mySectorData)
        .enter().append('svg')
        .attr('class', 'chart-label-svg')
        .attr('width', 70)
        .attr('height', 30)
        .attr('x', function (d, i) {
            return xScale(i) + 2;
        })
        .attr('y', function (d) {
            return height - yScale(d) - padding - 20;
        })
        .style('opacity', '0')
        .append('g');

    labelSVG.append('rect')
        .attr('class', 'chart-label-rect')
        .attr('width', 80)
        .attr('height', 30)
        .attr('x', 0)
        .attr('y', 0)
        .attr('fill', 'white');

    labelSVG.append('text')
        .attr('x', '50%')
        .attr('y', '50%')
        .attr('text-anchor', 'middle')
        .attr('alignment-baseline', 'middle')
        .attr('fill', '#000')
        .text(function (d) {
            return d;
        });

    var xAxisG = chart.append('g')
        .attr('class', 'axis')
        .attr('transform', 'translate(0,' + (height - padding) + ')')
        .call(xAxis);

    var rectTransitions = chartBars
        .on('mouseenter', function (d, i) {
            d3.select(this)
                .style('fill', d3.rgb('#ff9786').brighter(.3));
            d3.selectAll('.chart-label-svg')
                .filter(function (e, j) {
                    if (i === j) {
                        return this;
                    }
                })
                .style('opacity', '1.0');
        })
        .on('mouseleave', function (d, i) {
            d3.select(this)
                .style('fill', d3.rgb('#ff9786'));
            d3.selectAll('.chart-label-svg')
                .filter(function (e, j) {
                    if (i === j) {
                        return this;
                    }
                })
                .style('opacity', '0');
        })
        .transition()
        .duration(1000)
        .delay(function (d, i) {
            return i * 6;
        })
        .ease('elastic')
        .attr('height', function (d) {
            return yScale(d);
        })
        .attr('y', function (d) {
            return height - yScale(d) - padding;
        });


    //Organization-bar-chart
    var myOrganizationData = [],
        width = 250,
        height = 200,
        padding = 30,
        outerPadding = .2,
        barPadding = .1;

    for (var i = 0; i < 3; i++) {
        myOrganizationData.push(Math.ceil(Math.random() * 100) + 3);
    }

    var xScale = d3.scale.ordinal()
        .domain(d3.range(0, myOrganizationData.length))
        .rangeBands([padding, width - padding], barPadding, outerPadding);

    var yScale = d3.scale.linear()
        .domain([d3.max(myOrganizationData), 0])
        .range([height - (padding * 2), 0]);

    var xAxis = d3.svg.axis()
        .scale(xScale)
        .orient('bottom');

    var chart = d3.select('#organization-bar-chart')
        .style('background', '#fff')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

    var chartBars = chart.selectAll('rect').data(myOrganizationData)
        .enter().append('rect')
        .attr('class', 'chart-bar')
        .attr('width', function (d) {
            return xScale.rangeBand();
        })
        .attr('height', 0)
        .attr('fill', '#89a6ff')
        .attr('x', function (d, i) {
            return xScale(i);
        })
        .attr('y', height - padding);

    var labelSVG = chart.selectAll('svg').data(myOrganizationData)
        .enter().append('svg')
        .attr('class', 'chart-label-organization-svg')
        .attr('width', 70)
        .attr('height', 30)
        .attr('x', function (d, i) {
            return xScale(i) + 1;
        })
        .attr('y', function (d) {
            return height - yScale(d) - padding - 20;
        })
        .style('opacity', '0')
        .append('g');

    labelSVG.append('rect')
        .attr('class', 'chart-label-rect')
        .attr('width', 70)
        .attr('height', 30)
        .attr('x', 0)
        .attr('y', 0)
        .attr('fill', 'white');

    labelSVG.append('text')
        .attr('x', '50%')
        .attr('y', '50%')
        .attr('text-anchor', 'middle')
        .attr('alignment-baseline', 'middle')
        .attr('fill', '#000')
        .text(function (d) {
            return d;
        });

    var xAxisG = chart.append('g')
        .attr('class', 'axis')
        .attr('transform', 'translate(0,' + (height - padding) + ')')
        .call(xAxis);

    var rectTransitions = chartBars
        .on('mouseenter', function (d, i) {
            d3.select(this)
                .style('fill', d3.rgb('#89a6ff').brighter(.3));
            d3.selectAll('.chart-label-organization-svg')
                .filter(function (e, j) {
                    if (i === j) {
                        return this;
                    }
                })
                .style('opacity', '1.0');
        })
        .on('mouseleave', function (d, i) {
            d3.select(this)
                .style('fill', d3.rgb('#89a6ff'));
            d3.selectAll('.chart-label-organization-svg')
                .filter(function (e, j) {
                    if (i === j) {
                        return this;
                    }
                })
                .style('opacity', '0');
        })
        .transition()
        .duration(1000)
        .delay(function (d, i) {
            return i * 3;
        })
        .ease('elastic')
        .attr('height', function (d) {
            return yScale(d);
        })
        .attr('y', function (d) {
            return height - yScale(d) - padding;
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

