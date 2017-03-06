var yesDelete;
var form;
var fundingOrganizationCount = 0;
var implementingOrganizationCount = 0;
var tanzanianCountryCode = 'TZ';
var tanzaniaChosen = true;
var selectOptions;
var region;
var district;
var locationCount = 0;
var projectForm = $('#project-form');
var tanzanianRegion;
var tanzanianDistrict;

var Project = {
    /*
     * Show the confirm delete modal window.
     */
    confirmDelete: function () {
        var modal = $('#projectDeleteModal');
        var clone = modal.clone();

        clone.modal('show');

        yesDelete = clone.find('button#yes-delete');

        return this;
    },
    /*
     * Set the form to be submitted.
     */
    setForm: function (formParam) {
        form = formParam;

        return this;
    },
    /*
     * Trigger form submission.
     */
    submitForm: function () {
        form.submit();
    },
    /*
     * Add more funding Organization.
     */
    addFundingOrganization: function () {
        fundingOrganizationCount++;
        var newFundingOrganization = Project.clone($('#funding-org'), fundingOrganizationCount);

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(newFundingOrganization);

        $('#add-more-funding-organization').before(tempDiv);
        $('form select').select2();
    },
    /*
     * Add more Implementing Organization.
     */
    addImplementingOrganization: function () {
        implementingOrganizationCount++;
        var newImplementingOrganization = Project.clone($('#implementing-org'), implementingOrganizationCount)

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(newImplementingOrganization);

        $('#add-more-implementing-organization').before(tempDiv);
        $('form select').select2();
    },
    /*
     * Clone the form fields from the DOCUMENT.
     */
    clone: function (element, countByType) {
        var clone = element.clone();

        return clone.html().replace(/index/g, countByType)
    },
    /*
     * Remove the added block.
     */
    removeBlock: function (element, type) {
        if (type == 'implementing') {
            implementingOrganizationCount--;
        } else if (type == 'funding') {
            fundingOrganizationCount--;
        } else {
            locationCount--;
        }

        $(element).parent().remove();
    },
    changeFieldsForTanzania: function (edit) {
        Project.resetForm(edit);

        var clone = Project.clone($('#tz-location-clone'), locationCount);

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(clone);

        if (edit) {
            $('#location-wrap').find('#add-more-location-edit').before(tempDiv);
        } else {
            $('#location-wrap').find('#add-more-location').before(tempDiv);
        }
    },
    improviseForm: function (selectedCountryCode, edit) {
        if (selectedCountryCode == tanzanianCountryCode) {
            tanzaniaChosen = true;
            Project.changeFieldsForTanzania(edit);
        } else {
            tanzaniaChosen = false;
            Project.resetForm(edit);

            var clone = Project.clone($('#location-clone'), locationCount);

            var tempDiv = $('<div/>', {
                class: 'added-new-block'
            }).append(clone);

            if (edit) {
                $('#location-wrap').find('#add-more-location-edit').before(tempDiv);
            } else {
                $('#location-wrap').find('#add-more-location').before(tempDiv);
            }
        }

        $('form select').select2();
    },
    add: function (options) {
        selectOptions = options;

        return this;
    },
    to: function (regionSelect) {
        for (var i = 0; i < selectOptions.length; i++) {
            regionSelect.append($('<option/>', {
                value: selectOptions[i].code,
                html: selectOptions[i].value
            }));
        }
    },
    setDefault: function (defaultRegion, defaultDistrict) {
        region = defaultRegion;
        district = defaultDistrict;
    },
    addLocation: function (tanzaniaChosen, edit) {
        if (edit) {
            if (oldLocationCount != 0) {
                locationCount = parseInt(oldLocationCount);
                currentBudgetCount = locationCount;
            } else {
                currentLocationCount = parseInt(currentLocationCount);
                locationCount = currentLocationCount;
            }
        } else {
            locationCount = parseInt(oldLocationCount);
            currentLocationCount = locationCount;
        }

        locationCount++;
        oldLocationCount++;

        if (typeof currentLocationCount != "undefined") {
            currentLocationCount++;
        }
        var newLocation;

        if (!tanzaniaChosen) {
            newLocation = Project.clone($('#location-clone'), locationCount);
        } else {
            newLocation = Project.clone($('#tz-location-clone'), locationCount);
        }

        var tempDiv = $('<div/>', {
            class: 'added-new-block'
        }).append(newLocation);

        if (edit) {
            $('#add-more-location-edit').before(tempDiv);
        } else {
            $('#add-more-location').before(tempDiv);
        }

        $('form select').select2();
        edit = false;
    },
    resetForm: function (edit) {
        var fields = $('#location-wrap').children();

        fields.each(function (index, field) {
            if ($(field).is('div')) {
                field.remove();
            }
        });
    },
    fillDistricts: function (regionSelector) {
        var selectedRegion = regionSelector.val();

        var district = regionSelector.parent().next().find('select.district');

        var options = district.find('option');

        options.remove();

        district.append($('<option/>', {
            value: null,
            html: 'Select one of the following.'
        }).attr({
            selected: 'selected'
        }));

        $.each(districts[selectedRegion], function (index, value) {
            district.append($('<option/>', {
                value: index,
                html: value
            }));
        });

        $('form select').select2();
    }
};

$('a.delete-project').on('click', function () {
    Project.setForm($(this).parent().find('form#project-delete-form')[0]).confirmDelete();

    yesDelete.on('click', function () {
        Project.submitForm();
    });
});

$('a#duplicate-project').on('click', function () {
    Project.setForm($(this).parent().find('form#project-duplicate-form')[0]).submitForm();
});

$('#add-more-funding-organization').on('click', function () {
    Project.addFundingOrganization();
});

$('#add-more-implementing-organization').on('click', function () {
    Project.addImplementingOrganization();
});

var removeFunding = function (element) {
    Project.removeBlock(element, 'funding');
};

var removeImplementing = function (element) {
    Project.removeBlock(element, 'implementing');
};

var removeLocation = function (element) {
    Project.removeBlock(element, 'location');
};

$('#project-country').on('change', function () {
    locationCount = 0;
    var that = $(this);

    Project.setForm(that.parent().find('#project-form')).improviseForm(that.val());
});

$('#add-more-location').on('click', function () {
    if ($('#project-country').val() == 'TZ') {
        Project.addLocation(true);
    } else {
        Project.addLocation(false);
    }
});

$('#add-more-location-edit').on('click', function () {
    if ($('#project-country-edit').val() == 'TZ') {
        Project.addLocation(true, true);
    } else {
        Project.addLocation(false, true);
    }
});

$('form').delegate('.region', 'change', function () {
    Project.fillDistricts($(this));
});
