var Dashboard = {
    data: [],
    labels: ['Draft', 'Completed', 'Verified', 'Published'],
    barColors: ["#e15353", "#fcb651", "#4f7286", "#52cc88"],
    overlayBarColors: ["#edd0d0", "#f3dbb9", "#d5e8f3", "#b4eccd"],
    width: 300,
    height: 100,
    totalActivities: 0,
    rectangleSelection: '',
    canvas: '',
    widthOffset: 110,
    rectangleHeight: 6,
    spaceBetweenBars: 25,
    rectangleCurve: 3,
    xCoordinateOfBars: 80,
    yCoordinateOfLabelsAndValues:7,
    spaceBetweenBarAndValue : 85,
    widthScale: function (value) {
         var widthScale = d3.scaleLinear()
                           .domain([0, this.totalActivities])
                           .range([0, this.width - this.widthOffset]);

        return widthScale(value);
    },
    setData: function (data) {
        this.data = data;

        return this;
    },
    setTotalActivities: function (totalActivities) {
        this.totalActivities = totalActivities;
    },
    init: function (data, totalActivities) {
        this.defineCanvas()
            .setData(data)
            .setTotalActivities(totalActivities);

        var barOverlay = this.generateRectangle("bar", this.data, this.totalActivities, this.overlayBarColors);
        var bar = this.generateRectangle("barOverlay", this.data, null, this.barColors);

        this.addLabels()
            .addValues()

    },
    defineCanvas: function ()   {
        this.canvas = d3.select(".stats")
                        .append("svg")
                        .attr("width", this.width)
                        .attr("height", this.height);

        return this;
    },
    generateRectangle: function (selector, data, widthValue, colors) {
        this.rectangleSelection = this.canvas.selectAll(selector)
                                            .data(data)
                                            .enter()
                                            .append("rect");

        if (widthValue == null) {
            this.generateAnimation();
        }

        this.buildRectangle(widthValue, colors);
    },
    buildRectangle: function(widthValue, colors){
        return this.rectangleSelection.attr("width", function (d) {
                                            return (widthValue != null) ? Dashboard.widthScale(widthValue) : Dashboard.widthScale(d);
                                        })
                                        .attr("height", this.rectangleHeight)
                                        .attr("y", function (d, i) {
                                            return i * Dashboard.spaceBetweenBars;
                                        })
                                        .attr("x", this.xCoordinateOfBars)
                                        .attr("fill", function (d, i) {
                                            return colors[i];
                                        })
                                        .attr("rx", Dashboard.rectangleCurve)
                                        .attr("ry", Dashboard.rectangleCurve)
                                        .attr('id', (widthValue != null) ? 'rect-overlay' : 'rect');
    },
    generateAnimation: function () {
        return this.rectangleSelection.transition()
                                    .duration(10)
                                    .attr("width", function () {
                                        return Dashboard.widthScale(0);
                                    })
                                    .transition()
                                    .duration(3000)
                                    .attr("width", function (d) {
                                        return Dashboard.widthScale(d);
                                    });
    },
    addLabels: function () {
        this.canvas.selectAll("labels")
            .data(this.labels)
            .enter()
            .append("text")
            .attr("fill", "#484848")
            .attr("y", function (d, i) {
                return i * Dashboard.spaceBetweenBars + Dashboard.yCoordinateOfLabelsAndValues;
            })
            .text(function (d) {
                return d;
            });

        return this;
    },
    addValues: function () {
        this.canvas.selectAll("values")
            .data(this.data)
            .enter()
            .append("text")
            .attr("fill", "#484848")
            .attr("y", function (d, i) {
                return i * Dashboard.spaceBetweenBars + Dashboard.yCoordinateOfLabelsAndValues;
            })
            .attr("x", this.widthScale(this.totalActivities) + this.spaceBetweenBarAndValue)
            .text(function (d) {
                return d;
            });
    }
};
