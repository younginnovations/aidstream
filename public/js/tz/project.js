var yesDelete;
var form;
var fundingOrganizationCount = 0;
var implementingOrganizationCount = 0;
var projectForm = $('#project-form');
var tanzanianCountryCode = 'TZ';
var tanzaniaChosen = false;
var selectOptions;
var region;
var district;
var tanzanianRegion;
var tanzanianDistrict;
var locationCount = 0;

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

        projectForm.find('#funding-wrap').find('#add-more-funding-organization').before(tempDiv);
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

        projectForm.find('#implementing-wrap').find('#add-more-implementing-organization').before(tempDiv);
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
    changeFieldsForTanzania: function () {
        Project.resetForm();
        var regionName;
        var districtName;
        var regionWrapper = $('#region-wrap');
        var districtWrapper = $('#district-wrap');

        var regionFields = $('.region');
        var districtFields = $('.district');

        Project.setDefault(regionFields, districtFields);

        regionFields.hide();
        districtFields.hide();

        regionName = Project.rememberName(regionFields);
        districtName = Project.rememberName(districtFields);

        var options = [{code: '', value: 'Select one of the following.'}, {code: 1, value: 'one'}, {code: 2, value: 'two'}];
        var districts = [{code: '', value: 'Select one of the following.'}, {code: 1, value: 'D1'}, {code: 2, value: 'D2'}];

        var regionSelect = Project.addRegions(options, regionName);
        var districtSelect = Project.addDistricts(districts, districtName);

        tanzanianRegion = regionSelect;
        tanzanianDistrict = districtSelect;

        regionWrapper.append(regionSelect);
        districtWrapper.append(districtSelect);
    },
    rememberName: function (fields) {
        var name;

        fields.each(function (index, field) {
            name = field.name;
        });

        return name;
    },
    improviseForm: function (selectedCountryCode, edit) {
        if (selectedCountryCode == tanzanianCountryCode) {
            tanzaniaChosen = true;
            Project.changeFieldsForTanzania();
        } else {
            tanzaniaChosen = false;
            Project.resetForm(edit);
        }
    },
    addRegions: function (options, regionName) {
        regionName = regionName.replace(/index/g, locationCount);

        var regionSelect = $('<select/>', {
            class: 'form-control col-sm-4 region-select',
            name: regionName
        });

        Project.add(options).to(regionSelect);

        return regionSelect;
    },
    addDistricts: function (districts, districtName) {
        districtName = districtName.replace(/index/g, locationCount);

        var districtSelect = $('<select/>', {
            class: 'form-control col-sm-4',
            name: districtName,
            disabled: 'disabled'
        });

        Project.add(districts).to(districtSelect);

        return districtSelect;
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
    addLocation: function (tanzaniaChosen) {
        locationCount++;
        var newLocation;

        if (!tanzaniaChosen) {
            newLocation = Project.clone($('#location-clone'), locationCount);
        } else {
            newLocation = Project.clone($('#tz-location-clone'), locationCount);
        }

        var tempDiv = $('<div/>', {
            class: 'col-sm-12'
        }).append(newLocation);

        projectForm.find('#location-wrap').find('#add-more-location').before(tempDiv);
    },
    resetForm: function (edit) {
        var fields = $('#location-wrap').children();

        if (!edit) {
            fields.each(function (index, field) {
                if (index > 1 && $(field).is('div')) {
                    field.remove();
                }
            });

            if (tanzanianRegion && tanzanianDistrict) {
                tanzanianDistrict.remove();
                tanzanianRegion.remove();
            }

            if (region && district) {
                region.show();
                district.show();
            }
        } else {
            fields.each(function (index, field) {
                if ($(field).is('div')) {
                    field.remove();
                }
            });

            var clone = $(Project.clone($('#location-clone'), locationCount));

            $('input.region, input.district', clone).show();

            $('#location-wrap').find('#add-more-location').before($('<div/>').append(clone));
        }
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
    if (tanzaniaChosen) {
        Project.addLocation(true);
    } else {
        Project.addLocation(false);
    }
});
