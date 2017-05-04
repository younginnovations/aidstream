var Location = {
    maps: [],
    countryDetails: '',
    openedMap: '',
    removedDiv: '',
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
    getPolyBound: function (id) {
        return geoJson.features.filter(function (elem, i, geoJson) {
            if (elem.id2 == id) {
                return elem;
            }
        });
    },
    loadMap: function (countryDetails, source) {
        this.countryDetails = countryDetails;
        var countryCoordinates = [28, 85];
        var coordinates = [];
        var countryBoundaries;
        var polyPoints;

        var parentContainer = $(source).parent().parent();
        var administrativeContainer = parentContainer.find('.administrative');
        var pointContainer = administrativeContainer.find('.point');
        var mapContainer = parentContainer.find('.map_container');
        var mapWrapperContainer = mapContainer.parent('.map-wrapper');
        var displayStatus = mapWrapperContainer.css('display');

        if (displayStatus == 'none') {
            mapWrapperContainer.css('display', 'block');
            var country = parentContainer.find('.country').find('select').val();
            if (mapWrapperContainer.find('.badge-wrapper').length === 0) {
                mapWrapperContainer.append("<div class='badge-wrapper'><p>Click on the map to add locations.</p></div>");
            }
            if (administrativeContainer.find('.badge-wrapper').length === 0) {
                administrativeContainer.append("<div class='badge-wrapper'></div>");
            }
            mapContainer.css('display', 'block');
            if (country !== "") {
                countryCoordinates = Location.returnLatAndLong(country);
                countryBoundaries = Location.getPolyBound(country);
                if (countryBoundaries) {
                    polyPoints = countryBoundaries[0].geometry.coordinates;
                }
            }

            $.each(pointContainer, function (index, point) {
                var latitude = $(point).find('.latitude').val();
                var longitude = $(point).find('.longitude').val();
                var locationId = $(point).find('.locationName').attr('id');

                if ((latitude !== "" || latitude !== undefined) && (longitude !== "" && latitude !== undefined)) {
                    coordinates[index] = [];
                    coordinates[index] = [latitude, longitude, locationId];
                }
            });

            if (Location.maps[mapContainer.attr('id')] === undefined) {
                Location.maps[mapContainer.attr('id')] = Map.initMap(mapContainer.attr('id'));
                Map.flyTo(Location.maps[mapContainer.attr('id')], countryCoordinates, coordinates);
                Map.polyPoints = polyPoints;
            } else if (country !== "") {
                Map.flyTo(Location.maps[mapContainer.attr('id')], countryCoordinates);
            }

        } else {
            mapWrapperContainer.css('display', 'none');
            mapContainer.css('display', 'block');
        }
    },
    onCountryChange: function () {
      var modal = $('#countryChange');

      $('div.location').on('select2:selecting', 'select', function (e) {
          countryElement = $(e.target);
          countryVal = countryElement.val();
          selectElement = $(this);
          if ($(e.target).parent('.form-group').parent('.form-group').find('div.badge').length > 0) {
              $(this).select2("close");
              modal.modal('show');
          }
      })
      .on('change', 'select', function (e) {
          Location.clearUseMap();
          var parentContainer = $(this).closest('.country').parent();
          var mapContainer = parentContainer.find('.map_container');
          var mapId = mapContainer.attr('id');
          if (Location.maps[mapId] !== undefined) {
              Location.maps[mapId].remove();
              Location.maps[mapId] = undefined;
              Location.loadMap();
          }
      });
    },
    clearLocations: function () {
        var buttons = countryElement.parent('.form-group').parent('.form-group').find('div.map-wrapper').find('button.remove_from_location');
        $.each(buttons, function(index, value){
          Map.deleteLocation($(value).attr('id'));
        });
        countryElement.parent('.form-group').parent('.form-group').find('div.badge').remove();
    },
    clearUseMap: function(){
      var buttons = $('.view_map');
      if(buttons.length > 0){
        $.each(buttons, function(index, button){
          if(Location.canOpen($(button))){
            $(button).css('display', 'block');
          } else {
            $(button).css('display', 'none');
          }
        });
      }
    },
    closeOpenedMap: function (countryDetails) {
        $(document).on('click', function (e) {
            if ($(e.target).hasClass('remove_from_location')) {
                Map.deleteLocation($(e.target).attr('id'));
            } else if ($(e.target).hasClass('close-map')) {
                $('.map-wrapper').css('display', 'none');
            } else if ($(e.target).closest('.map-wrapper').length == 1) {
                $(e.target).closest('.map-wrapper').css('display', 'block');
            } else if ($(e.target).hasClass('view_map')) {
                if (Location.canOpen($(e.target))) {
                    if (Location.openedMap !== "") {
                        $(Location.openedMap).css('display', 'none');
                    }
                    Location.openedMap = $(e.target).parent().parent('.form-group').find('.map-wrapper');
                    Location.loadMap(countryDetails, $(e.target));
                }
            } else {
                $('.map-wrapper').css('display', 'none');
            }
        });
    },
    onCountryDelete: function () {
        var self = this;
        $(document).on('click', '.btn_remove', function () {
            var source = $(this).closest('.modal').attr('data-source');
            self.maps[source] = undefined;
            $('#removeDialog').remove();
            $('.modal-in').remove();
        });
    },
    canOpen: function (button) {
        var countryVal = button.parent('div').parent('div').find('select').val();
        if(countryVal === "") {
          return false;
        }
        return Location.countryMapAvailable(countryVal);
    },
    countryMapAvailable: function(val){
      var available = false;
      $.each(geoJson.features, function(index, data) {
        if(val == data.id2) {
          available =  true;
        }
      });
      return available;
    },
    prepareElements: function () {
        var button = $('button.view_map');
        button.attr('data-toggle', 'tooltip');
        button.attr('title', 'Select country first to use map.');
        button.tooltip();
    }
};

