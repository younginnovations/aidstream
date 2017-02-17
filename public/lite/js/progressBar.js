var ProgressBar = {
    totalFields: 20,
    filledFields: {
        'activity_identifier': false,
        'activity_title': false,
        'activity_status': false,
        'sector': false,
        'start_date': false,
        'end_date': false,
        'general_description': false,
        'objectives': false,
        'target_groups': false,
        'location[0][country]': false,
        'location[0][administrative][0][point][0][latitude]': false,
        'location[0][administrative][0][point][0][longitude]': false,
        'funding_organisations[0][organisation_name]': false,
        'funding_organisations[0][organisation_type]': false,
        'implementing_organisations[0][organisation_name]': false,
        'implementing_organisations[0][organisation_type]': false,
        'outcomes_document[0][document_title]': false,
        'outcomes_document[0][document_url]': false,
        'annual_report[0][document_title]': false,
        'annual_report[0][document_url]': false
    },
    countOfFilledFields: 0,
    canvas: '',
    group: '',
    rectangle: '',
    height: 30,
    width: 210,
    rectWidth: 203,
    rectHeight: 6,
    basics: 0,
    locations: 0,
    involvedOrganisations: 0,
    resultAndReports: 0,
    totalBasicsField: 9,
    totalLocationField: 3,
    totalInvolvedOrganisationField: 4,
    totalResultAndReportsField: 4,
    completedLocalisedText: 'completed',
    calculateProgressBar: function (completedText) {
        this.completedLocalisedText = completedText;

        this.defineCanvas()
            .defineGroup();

        this.checkValue()
            .displayPercentage();

        var barOverlay = this.generateRectangle(this.width, "bar-overlay");
        var bar = this.generateRectangle(false, "bar");
    },
    defineCanvas: function () {
        this.canvas = d3.select('#activity-progress-bar')
            .append("svg")
            .attr("height", this.height)
            .attr("width", this.width);

        return this;
    },
    defineGroup: function () {
        this.group = this.canvas.append("g");
    },
    generateRectangle: function (width, selector) {
        this.rectangle = this.group.selectAll(selector)
            .data([this.countOfFilledFields])
            .enter()
            .append("rect")
            .attr("width", function (d) {
                return (width) ? ProgressBar.width : ProgressBar.scaleWidth(d);
            })
            .attr("height", this.rectHeight)
            .attr("fill", "#52cc88")
            .style("opacity", function () {
                return (width) ? .3 : 1;
            })
            .attr("rx", 3)
            .attr("ry", 3)
            .attr("id", selector);
    },
    updateWidthOfRectangle: function (selector) {
        $('#' + selector).attr("width", ProgressBar.scaleWidth(this.countOfFilledFields));
    },
    scaleWidth: function (width) {
        var scaleWidth = d3.scaleLinear()
            .domain([0, this.totalFields])
            .range([0, this.width]);

        return scaleWidth(width);
    },
    scalePercentage: function (value) {
        var percentage = d3.scaleLinear()
            .domain([0, this.totalFields])
            .range([0, 100]);

        return percentage(value);
    },
    changeStatusOfField: function (name, status) {
        if (this.filledFields[name] != undefined) {
            this.filledFields[name] = status;
        }
    },
    updateFilledFields: function () {
        this.countOfFilledFields = 0;
        this.basics = 0;
        this.locations = 0;
        this.involvedOrganisations = 0;
        this.resultAndReports = 0;

        $.each(this.filledFields, function (key, value) {
            if (value === true) {
                (ProgressBar.countOfFilledFields <= ProgressBar.totalFields) ? ProgressBar.countOfFilledFields += 1 : '';
            }
            ProgressBar.calculateFieldProgress(key, value);
        });

        ProgressBar.addClassToField();
    },
    displayPercentage: function () {
        this.canvas.selectAll("percentage")
            .data([this.countOfFilledFields])
            .enter()
            .append("text")
            .attr("fill", "#484848")
            .attr("x", 20)
            .attr("y", 25)
            .style("opacity", 0.5)
            .style("font-family", "OpenSans")
            .style("font-size", 12)
            .style("font-weight", "normal")
            .style("font-style", "italic")
            .style("line-height", "normal")
            .style("font-stretch", "normal")
            .style("letter-spacing", "normal")
            .attr("id", "percentage")
            .text(function (d) {
                return Math.round(ProgressBar.scalePercentage(d)) + '%' + ' ' + ProgressBar.completedLocalisedText;
            });
    },
    updatePercentage: function () {
        $('#percentage').html(Math.round(ProgressBar.scalePercentage(this.countOfFilledFields)) + "% completed");
    },
    calculate: function () {
        $('input, select, textarea').on('change', function () {
            var name = $(this).attr('name');
            ProgressBar.checkFields(this, name);
            ProgressBar.updateFilledFields();
            ProgressBar.updatePercentage();
            ProgressBar.updateWidthOfRectangle("bar");
        });
    },
    checkValue: function () {
        $('input, select, textarea').not("[name = '_token']").each(function () {
            var name = $(this).attr('name');
            if ((name != "outcomes_document[0][document_link_id]") && (name != "annual_report[0][document_link_id]")) {
                ProgressBar.checkFields(this, name);
            }
        });
        ProgressBar.updateFilledFields();
        ProgressBar.updatePercentage();

        return this;
    },
    checkFields: function (source, name) {
        if ($(source).val() == "") {
            ProgressBar.changeStatusOfField(name, false);
        } else {
            ProgressBar.changeStatusOfField(name, true);
        }
    },
    calculateFieldProgress: function (key, status) {
        var fieldType = $("[name = '" + key + "']").closest(".form__block").attr("id");

        if (fieldType == 'basics' && status == true) {
            this.basics++;
        }

        if (fieldType == 'location' && status == true) {
            this.locations++;
        }

        if (fieldType == 'involved-organisations' && status == true) {
            this.involvedOrganisations++;
        }

        if (fieldType == 'results-and-reports' && status == true) {
            this.resultAndReports++;
        }
    },
    addClassToField: function () {
        if (this.basics == this.totalBasicsField) {
            $("a[href = '#basics']").parent().addClass('nav--completed');
        } else {
            $("a[href = '#basics']").parent().removeClass('nav--completed');
        }

        if (this.locations == this.totalLocationField) {
            $("a[href = '#location']").parent().addClass('nav--completed');
        } else {
            $("a[href = '#location']").parent().removeClass('nav--completed');
        }

        if (this.involvedOrganisations >= this.totalInvolvedOrganisationField) {
            $("a[href = '#involved-organisations']").parent().addClass('nav--completed');
        } else {
            $("a[href = '#involved-organisations']").parent().removeClass('nav--completed');
        }

        if (this.resultAndReports >= this.totalResultAndReportsField) {
            $("a[href = '#results-and-reports']").parent().addClass('nav--completed');
        } else {
            $("a[href = '#results-and-reports']").parent().removeClass('nav--completed');
        }
    },
    onMapClicked: function () {
        $('.map_container').on('click', function () {
            var name = 'location[0][administrative][0][point][0][latitude]';
            for (var i = 0; i < 2; i++) {
                ProgressBar.changeStatusOfField(name, true);
                name = 'location[0][administrative][0][point][0][longitude]';
            }
            ProgressBar.updateFilledFields();
            ProgressBar.updatePercentage();
            ProgressBar.updateWidthOfRectangle("bar");
        });
    },
    setTotalFields: function (value) {
        this.totalFields = value;
    },
    setLocationFields: function (value) {
        this.totalLocationField = value;
    },
    addFilledFieldsForTz: function () {
        this.filledFields['location[0][administrative][0][region]'] = false;
        this.filledFields['location[0][administrative][0][district]'] = false;
    }
};