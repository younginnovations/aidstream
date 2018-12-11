@extends('np.mcpAdmin.includes.sidebar')

@section('title', 'Dashboard')

@section('content')
	{{Session::get('message')}}

	<div class="col-xs-9 col-lg-9 content-wrapper">
		@include('includes.response')
		<div id="xml-import-status-placeholder"></div>
		<div class="panel panel-default">
			<div class="panel__heading dashboard-panel__heading">
				<div>
					<div class="panel__title">Dashboard</div>
					<p>Find all your stats here</p>
				</div>
			</div>
			<div class="panel__body">
				<div class="panel-chart-grid">
					<div class="panel-chart-section organization">
						<div class="section-card">
							<div class="header">
								<h3>Organization Type</h3>
							</div>
							<div class="body">
								<div class="stats">
									<h1 class="number">280</h1>
									<span class="text">Total organizations</span>
								</div>
								<div class="bar-chat-wrapper">
									<div class="organization-chart-container" id="organization-chart">
										<svg width="250" height="320"></svg>
									</div>
								</div>
								<div class="secondary-stats">
									<ul>
										<li>
											<span class="sec-number">100</span>
											<span class="sec-text">Community org.</span>
										</li>
										<li>
											<span class="sec-number">180</span>
											<span class="sec-text">Local NGO org.</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-chart-section projects">
						<div class="section-card">
							<div class="header">
								<h3>Projects</h3>
							</div>

							<div class="body pie-chart">
								<div>
									<div class="pie-svg-wrap">
										<div id="svg"></div>
									</div>
									<ul data-pie-id="svg">
										<li class="color-light-maroon" data-value="8">Active Projects <span>8</span></li>
										<li class="color-purple" data-value="12">Inactive Projects <span>12</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-chart-section sector">
						<div class="section-card">
							<div class="header">
								<h3>Sectors</h3>
							</div>
							<div class="body">
								<div class="stats">
									<h1 class="number">280</h1>
									<span class="text">Total sectors</span>
								</div>
								<div class="bar-chat-wrapper">
									<div class="sector-chart-container" id="sector-chart">
										<svg width="460" height="320"></svg>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-chart-section budget">
						<div class="section-card">
							<div class="header">
								<h3>Budget</h3>
							</div>
							<div class="body">
								<div class="stats">
									<h1 class="number"><span class="count" id="budgetTotal"><small>$</small><span id="totalBudget">{{ array_sum($budget) }}</span><small id="placeValue"></small></span></h1>
									<span class="text">Total Budget</span>

									<div class="highest-budget">Highest budget in an activity: <span id="maxBudget">${{ $budget[0] }}</span></div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-chart-section activities">
						<div class="section-card">
							<div class="header">
								<h3>Activities</h3>
							</div>
							<div class="body pie-chart">
								<div class="stats">
									<h1 class="number">{{ $activitiesCount }}</h1>
									<span class="text">Total activities</span>
								</div>
								<div class="pie-chart-wrap">
									<div>
										<div class="pie-svg-wrap">
											<div id="activities-svg"></div>
										</div>
									</div>
								</div>
								<div class="pie-chart-datalist">
									<ul data-pie-id="activities-svg">
										<li class="color-grey" data-value="4"><span class="text">Draft</span> <span class="number">4 <em style="width: 17%"></em></span></li>
										<li class="color-light-maroon" data-value="12"><span class="text">Completed</span> <span class="number">12 <em style="width: 50%"></em></span></li>
										<li class="color-purple" data-value="8"><span class="text">Published</span> <span class="number">8 <em style="width: 33%"></em></span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@stop
	</div>

@section('script')
	<script src="{{url('/lite/js/dashboard.js')}}"></script>
	<script src="{{url('/lite/js/lite.js')}}"></script>
	<script src="{{url('/np/js/pie-chart/modernizr.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.5.1/snap.svg-min.js"></script>
	<script src="{{url('/np/js/pie-chart/pizza.min.js')}}"></script>
	<script>
        $(window).load(function () {
            Pizza.init();
        });

        //Sector-bar-chart
        var sector_static_data = [{
            sector_area: 'Education',
            sector_count: 42
        }, {
            sector_area: 'Health',
            sector_count: 102
        }, {
            sector_area: 'Traning',
            sector_count: 160
        }, {
            sector_area: 'Biodiversity',
            sector_count: 82
        }, {
            sector_area: 'Preservation',
            sector_count: 48
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
        var organization_static_data = [{
            organization_area: 'Community',
            organization_count: 100
        }, {
            organization_area: 'Local NGO',
            organization_count: 180
        }];

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
@stop
