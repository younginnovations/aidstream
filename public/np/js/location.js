var NpLocation = {
    map: '',
    coordinates: [52.48626, -1.89042],
    countryDetails: '',
    openedMap: '',
    returnLatAndLong: function (country) {
        var coordinates = '';
        $.each(this.countryDetails[0], function (index, countries) {
            $.each(countries, function (key, value) {
                if (value == country) {
                    coordinates = countries.latlng;
                    return false;
                }
            });
        });
        return coordinates;
    },
    loadMap: function (countryDetails, source) {
        this.countryDetails = countryDetails;
        var parentContainer = $(source).parent().parent();
        var pointContainer = parentContainer.find('.point');
        var mapContainer = parentContainer.find('.map_container');

        var displayStatus = pointContainer.css('display');

        if (displayStatus == 'none') {
            pointContainer.css('display', 'block');
            mapContainer.css('display', 'block');
            var latitude = pointContainer.find('.latitude');
            var longitude = pointContainer.find('.longitude');

            var latitudeValue = latitude.val();
            var longitudeValue = longitude.val();
            var country = parentContainer.closest(".administrative").parent().find('.country').children('select').val();

            TzLocation.toggleLatLongAndMap(mapContainer, latitude, longitude);
            if (latitudeValue != "" && longitudeValue != "") {
                TzLocation.coordinates = [latitudeValue, longitudeValue];
            }

            if (country != "") {
                TzLocation.coordinates = (latitudeValue != "" && longitudeValue != "") ? TzLocation.coordinates : TzLocation.returnLatAndLong(country);
            }

            if (TzLocation.map) {
                TzLocation.map.remove();
            }
            TzLocation.map = Map.initMap(mapContainer.attr('id'), TzLocation.coordinates);
        } else {
            pointContainer.css('display', 'none');
            mapContainer.css('display', 'block');
        }
    },
    onCountryChanged: function () {
        $('.country').on('change', 'select', function () {
            var parentContainer = $(this).closest('.country').parent();
            parentContainer.find('.latitude').val('');
            parentContainer.find('.longitude').val('');
        });
    },
    toggleLatLongAndMap: function (mapContainerSelector, latitudeSelector, longitudeSelector) {
        mapContainerSelector.css('display', 'block');
        latitudeSelector.parent().removeClass('hidden');
        longitudeSelector.parent().removeClass('hidden');
    },
    closeOpenedMap: function (countryDetails) {
        $(document).on('click', function (e) {
            if ($(e.target).closest('.point').length == 1) {
                $(e.target).closest('.point').css('display', 'block');
            }
            else if ($(e.target).hasClass('view_map')) {
                if (TzLocation.openedMap != "") {
                    $(TzLocation.openedMap).css('display', 'none');
                }
                TzLocation.openedMap = $(e.target).parent().parent().find('.point');
                TzLocation.loadMap(countryDetails, $(e.target));
            } else {
                $('.point').css('display', 'none');
            }
        })
    }
};