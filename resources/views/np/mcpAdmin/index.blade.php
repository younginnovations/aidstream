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
				<div class="panel__status text-center">
					<div class="col-sm-4">
						<h2>Total Activities</h2>
						<span class="count">{{ $activitiesCount }}</span>
						<div class="published-num">
							<span>No. of activities published to IATI:</span>0
						</div>
					</div>
					<div class="col-sm-4">
						<h2>Activity by Status</h2>
						<div class="stats">
							<div class="background-masker header-top"></div>
							<div class="background-masker header-left"></div>
							<div class="background-masker header-right"></div>
							<div class="background-masker header-bottom"></div>
							<div class="background-masker subheader-left"></div>
							<div class="background-masker subheader-right"></div>
							<div class="background-masker subheader-bottom"></div>
							<div class="background-masker content-top"></div>
							<div class="background-masker content-first-end"></div>
							<div class="background-masker content-second-line"></div>
							<div class="background-masker content-second-end"></div>
							<div class="background-masker content-third-line"></div>
							<div class="background-masker content-third-end"></div>
							<svg width="300" height="100"><rect width="190" height="6" y="0" x="80" fill="#edd0d0" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="25" x="80" fill="#f3dbb9" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="50" x="80" fill="#d5e8f3" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="75" x="80" fill="#b4eccd" rx="3" ry="3" id="rect-overlay"></rect><rect width="162.85714285714286" height="6" y="0" x="80" fill="#e15353" rx="3" ry="3" id="rect"></rect><rect width="0" height="6" y="25" x="80" fill="#fcb651" rx="3" ry="3" id="rect"></rect><rect width="0" height="6" y="50" x="80" fill="#4f7286" rx="3" ry="3" id="rect"></rect><rect width="27.142857142857142" height="6" y="75" x="80" fill="#52cc88" rx="3" ry="3" id="rect"></rect><text fill="#484848" y="7">Draft</text><text fill="#484848" y="32">Completed</text><text fill="#484848" y="57">Verified</text><text fill="#484848" y="82">Published</text><text fill="#484848" y="7" x="275">6</text><text fill="#484848" y="32" x="275">0</text><text fill="#484848" y="57" x="275">0</text><text fill="#484848" y="82" x="275">1</text></svg></div>
					</div>
					<div class="col-sm-4">
						<h2>Total Budget</h2>
						<span class="count" id="budgetTotal"><small>$</small><span id="totalBudget">{{ array_sum($budget) }}</span><small id="placeValue"></small></span>
						<div class="highest-budget">Highest budget in an activity: <span id="maxBudget">${{ $budget[0] }}</span></div>
					</div>
				</div>
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
				</div>
			</div>
		</div>
		@stop

		@section('script')
			<script src="{{url('/lite/js/dashboard.js')}}"></script>
			<script src="{{url('/lite/js/lite.js')}}"></script>
			<script type="text/javascript" src="https://d3js.org/d3.v4.min.js"></script>
			<script>
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
                    organization_count: 42
                }, {
                    organization_area: 'Local NGO',
                    organization_count: 102
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
