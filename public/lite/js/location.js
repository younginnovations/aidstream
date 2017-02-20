var Location = {
    map: '',
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
    loadMap: function (countryDetails) {
        this.countryDetails = countryDetails;

        $('form .map_container').each(function () {
            var latitude = $(".latitude").val();
            var longitude = $(".longitude").val();
            var country = $("[name = 'country']").val();

            if (latitude != "" && longitude != "") {
                Location.coordinates = [latitude, longitude];
            }
            if (country != "") {
                $('.location').removeClass('hidden');
                Location.coordinates = (latitude != "" && longitude != "") ? Location.coordinates : Location.returnLatAndLong(country);
            }

            Location.map = initMap($(this).attr('id'), Location.coordinates);
        });
    },
    onCountryChange: function () {
        $("[name = 'country']").on('change', function () {
            var country = $(this).val();
            if (country != "") {
                $('.location').removeClass('hidden');
                var coordinates = Location.returnLatAndLong(country);
                flyTo(Location.map, coordinates);
            }
        });
    }
};
