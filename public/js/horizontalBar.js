hello(finalData.activity_status);
function hello(data) {
    var barHeight = 35;
    var height = barHeight * data.length;
    var marginHorz = { top: 20, right: 20, bottom: 40, left: 100},
        widthHorz = 480 - marginHorz.left - marginHorz.right,
        heightHorz = height;

    var labelSpace = 60;
// innerMargin = widthHorz/2+labelSpace;

    var dataRange = d3.max(data.map(function(d) { return Math.max(d.values) }));
    var total = d3.scale.linear().domain([0, dataRange]).range([0, widthHorz - labelSpace]);

    var yScale = d3.scale.ordinal().rangeRoundBands([0,heightHorz]);;
    var xScale = d3.scale.linear().range([0,widthHorz]);

    var xAxisHorz = d3.svg.axis()
        .scale(xScale)
        .orient("bottom");

    var yAxisHorz = d3.svg.axis()
        .scale(yScale)
        .orient("left");

    xScale.domain([0, d3.max(data, function(d) { return d.values; })]);
    yScale.domain(data.map(function(d) {return d.region; }));


    var svgHorz = d3.select("#horizontalBarChart").append("svg")
        .attr("width", widthHorz + marginHorz.left + marginHorz.right)
        .attr("height", heightHorz + marginHorz.top + marginHorz.bottom)
        .append("g")
        .attr("transform", "translate(" + marginHorz.left + "," + marginHorz.top + ")");

    var divNode = d3.select("#horizontalBarChart").node();

    //vertical lines
    svgHorz.selectAll(".vline").data(d3.range(10)).enter()
        .append("line")
        .attr("x1", function (d) {
            return d * (widthHorz/10);
        })
        .attr("x2", function (d) {
            return d * (widthHorz/10);
        })
        .attr("y1", function (d) {
            return 10;
        })
        .attr("y2", function (d) {
            return heightHorz;
        })
        .style("stroke", "#eee");

    data.forEach(function(d) {
        d.region = d.region;
        d.values = +d.values;
        d.targets = +d.targets;
    });

    svgHorz.append("g")
        .attr("class", "y axis")
        .call(yAxisHorz)
        .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 5)
        .attr("dy", 200)
        .style("text-anchor", "end");

    svgHorz.selectAll("rect")
        .data(data)
        .enter().append("rect")
        .attr("class", "bar")
        .style("fill", "#B2BEC4  ")
        .transition()
        .ease("quad-out")
        .duration(4000)
        .delay(0)
        .attr("x", 0)
        .attr("width", function (d){
            return total(d.values);
        })
        .attr("y", function (d, i){
            return i * (height / data.length);
        })
        .attr("height", barHeight-5);

    svgHorz.selectAll("text.value")
        .data(data)
        .enter()
        .append("text")
        .text(function(d) { return d.values.toLocaleString(); })
        .attr("class","bar")
        .attr("y", function(d,i){
            return i * (heightHorz / data.length);
        })
        .attr("dx",function (d){
            return total(d.values) + 10})
        .attr("dy", 25);
}