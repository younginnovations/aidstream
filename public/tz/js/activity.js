var Activity = {
    changeRegionAndDistrict: function () {
        $('.location').on('change', '.region', function () {
            var region = $(this).val();
            var districtSelector = $(this).parent().siblings('.district-container').find('select');
            Activity.appendDistrict(region, districtSelector);
        })
    },
    appendDistrict: function (region, districtSelector, selectedValue) {
        if (region != "") {
            var options = districtSelector.find('option');
            options.remove();

            if (!selectedValue) {
                districtSelector.siblings('span').find('.selection').find('.select2-selection__rendered').html("Choose a district");
            }

            $.each(districts[0][region], function (index, value) {
                if (selectedValue === value) {
                    districtSelector.append("<option value='" + index + "' selected> " + value + "</option>");
                } else {
                    districtSelector.append("<option value='" + index + "'>" + value + "</option>");
                }
            });
        }
    },
    loadDistrictIfRegionIsPresent: function (selectedDistricts) {
        var regions = $('.location').find('.region-container');
        $.each(regions, function (index, region) {
            var parentContainer = $(region).parent().parent().parent();
            if (index > 0 && $(region).parent().parent().parent().hasClass('added-new-block')) {
                $(region).parent().addClass('added-new-block');
            }
            var selectedRegion = $(region).find('.region').val();
            var selectedCountry = parentContainer.find('.country').children('select').val();
            if (selectedCountry != "TZ") {
                $(region).parent().find('.district-container').remove();
                $(region).closest('.administrative').next().remove();
                $(region).remove();
            } else {
                district = $(region).parent().find('.district-container').children('select');
                Activity.appendDistrict(selectedRegion, district, selectedDistricts[selectedRegion]);
            }
        });
    },
    removeCountriesExceptTz: function () {
        var country = $('.country > select').first();
        var options = country.find('option');
        options.remove();

        country.append("<option value='TZ'>TZ- TANZANIA, UNITED REPUBLIC OF</option>")
    },
    displayAddMoreCountry: function () {
        var location = $('.location');
        var tzContainer = location.find('.form-group').first();
        var country = location.find('.country');
        var countryLength = country.length;
        var addMoreCountry = $('#displayAddMore');
        var addMoreCountryContainer = addMoreCountry.parent();

        if (countryLength > 1) {
            addMoreCountry.removeClass('No');
            addMoreCountry.addClass('Yes');
            location.append(
                "<button class='add-location' type='button' onclick='Activity.addLocation($(this))'> " +
                "Add another country" +
                "</button>"
            );
        }
        tzContainer.after(addMoreCountryContainer);

        addMoreCountry.on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass("Yes")) {
                $(this).removeClass('Yes');
                $(this).addClass('No');
                $('.new-location-block').remove();
                $('.add-location').remove();
            } else if ($(this).hasClass("No")) {
                $(this).removeClass('No');
                $(this).addClass('Yes');
                Activity.addLocation($(this))
            }
        });
        $('.option-to-add-more').nextAll('.form-group').addClass('added-new-block new-location-block');
    },
    addLocation: function (source) {
        var locationContainer = $(source).closest('.location');
        var collection = $('.location-container');
        var locationCount = locationContainer.find('.country').length;

        var proto = collection.html().replace(/locationCount/g, locationCount);
        proto = proto.replace(/administrativeCount/g, 0);
        proto = $(proto).addClass('added-new-block new-location-block');
        locationContainer.append(proto);

        var newBlock = locationContainer.find('.new-location-block');
        $.each(newBlock, function (index, div) {
            $(div).find('.region-container').remove();
            $(div).find('.district-container').remove();
            $(div).find("[data-collection = 'administrative']").remove();
        });

        if ($('.add-location').length == 0) {
            locationContainer.append(
                "<button class='add-location' type='button' onclick='Activity.addLocation($(this))'> " +
                "Add another country" +
                "</button>"
            );
        }

        $('form select').select2();
    },
    addMoreAdministrative: function () {
        $('.add_another_location').on('click', function () {
            var administrativeContainer = $(this).parent().parent().find('.administrative');
            var collection = $('.administrative-container');
            var administrativeCount = administrativeContainer.children('.form-group').length;
            var locationCount = $(this).closest('.country').length;

            var proto = collection.html().replace(/locationCount/g, 0);
            proto = proto.replace(/administrativeCount/g, administrativeCount);
            proto = $(proto).addClass('added-new-block');
            administrativeContainer.append(proto);

            $('form select').select2();
        });
    },
    deleteCountryOnClick: function () {
        $('.collection_form').on('click', '.remove_location', function () {
            if ($('.new-location-block').length > 1) {
                $(this).closest('.new-location-block').remove();
            }
        });
    }
};