var TzLocation = {
    map: [],
    coordinates: [52.48626, -1.89042],
    countryDetails: '',
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
            TzLocation.map.push(initMap(mapContainer.attr('id'), TzLocation.coordinates));
        } else {
            pointContainer.css('display', 'none');
            mapContainer.css('display', 'block');
        }
    },
    onCountryChanged: function () {
        $('.country').on('change', 'select', function () {
            var country = $(this).val();
            if (country != "" && TzLocation.map != "") {
                var coordinates = TzLocation.returnLatAndLong(country);
                $.each(TzLocation.map, function (index, map) {
                    flyTo(map, coordinates);
                });
            }
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
                $(e.target).closest('.point').display('block');
            }
            else if ($(e.target).hasClass('view_map')) {
                TzLocation.loadMap(countryDetails, $(e.target));
            } else {
                $('.point').css('display', 'none');
            }
        })
    }
    // onLatAndLongChange: function () {
    //     $('.location').on('change', '.latitude, .longitude', function () {
    //         var source = $(this).attr('class');
    //         var mapContainerId = $(this).parent().parent().find('.map_container').attr('id');

    // if (source === 'latitude') {
    //     var latitude = $(this).val();
    //     var longitude = $(this).parent().parent().find('.longitude').val();
    // }

    // if (TzLocation.map != "" && latitude != "" && longitude != "") {
    //     console.log(TzLocation.map);
    // flyTo(TzLocation.ma)
    // }
    // });
    // }
};