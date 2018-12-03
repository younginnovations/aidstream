/* exported initMap */
/* globals L */


var Map = {
    administrativeCount: [],
    jPaneApi: '',
    polyPoints: [],
    initMap: function (elem) {
        var self = this;
        var center = [52.48626, -1.89042];

        var map = new L.Map(elem, {
            center: center,
            zoom: 6
        }).addLayer(new L.TileLayer(
            'http://api.tiles.mapbox.com/v3/younginnovations.ijg2d43b/{z}/{x}/{y}.png', {
                attribution: "<a href='https://www.mapbox.com/about/maps/' target='_blank'>&copy; Mapbox &copy; OpenStreetMap</a> | " +
                "<a href='http://www.mapquest.com/' target='_blank' title='Nominatim Search Courtesy of Mapquest'>MapQuest</a>"
            }
        ));
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();

        map.on('click', function (e) {
            var marker = L.marker(e.latlng);
            if (Map.isMarkerInsidePolygon(marker, Map.polyPoints)) {
                ProgressBar.triggerMapClick();
                marker.addTo(map);
                self.checkField(elem, e.latlng, marker);
            }
        });

        return map;
    },
    flyTo: function (map, countryCoordinates, latLong) {
        var self = this;
        if (Array.isArray(latLong) && latLong.length > 0) {
            $.each(latLong, function (index, values) {
                var marker = L.marker({lat: parseFloat(values[0]), lng: parseFloat(values[1])}).addTo(map);
                self.setMarkersAttribute(marker, values[2]);
            });
        }
        map.panTo(countryCoordinates);
        map.invalidateSize();
    },
    isMarkerInsidePolygon: function (marker, poly) {
        var x = marker.getLatLng().lat, y = marker.getLatLng().lng;
        var polyPoints = poly[0];

        var inside = false;
        for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            var xi = polyPoints[i][1], yi = polyPoints[i][0];
            var xj = polyPoints[j][1], yj = polyPoints[j][0];

            var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }

        return inside;
    },
    checkField: function (elem, latLong, marker) {
        var mapContainer = $('#' + this.escapeBrackets(elem));
        var administrativeContainer = mapContainer.parent().parent().find('.administrative');
        var countryId = administrativeContainer.parent().find('.country').find('select').attr('id');
        var countryIndex = this.getIndex(countryId, 0);
        var currentAdministrativeCount = administrativeContainer.children('.form-group').length;
        var pointContainer = administrativeContainer.find('.point');
        var mapWrapperContainer = mapContainer.parent('.map-wrapper');
        if(mapWrapperContainer.find(".close-map").length === 0){
            mapWrapperContainer.append("<div class='btn close-map'>Done</div>");
        }

        if (!pointContainer.hasClass('added')) {
            var locationNameId = pointContainer.find('.locationName').attr('id');
            pointContainer.find('.latitude').val(latLong.lat);
            pointContainer.find('.longitude').val(latLong.lng);
            this.setLocationName(mapWrapperContainer, administrativeContainer, locationNameId, latLong.lat, latLong.lng);
            this.setMarkersAttribute(marker, locationNameId);
            pointContainer.addClass('added');
        } else {
            this.generateField(mapWrapperContainer, administrativeContainer, countryIndex, currentAdministrativeCount, countryId, latLong, marker);
        }
    },
    getIndex: function (string, index) {
        var pattern = /(\[\d+\])/g;
        var count = pattern.exec(string)[index];

        return count.replace(/[\[\]]/g, '');
    },
    escapeBrackets: function (elem) {
        return elem.replace(/([\[\]])/g, '\\$1');
    },
    setLocationName: function (mapWrapperContainer, administrativeContainer, field, lat, long) {
        var self = this;
        self.asyncRequest('GET', '/reverseGeoCode', {'latitude': lat, 'longitude': long}).success(function (response) {
            var pane = self.findDiv(mapWrapperContainer, '.badge-wrapper');
            pane.jScrollPane();
            self.jPaneApi = pane.data('jsp');

            mapWrapperContainer.find('p').remove();
            administrativeContainer.find('#' + self.escapeBrackets(field)).val(response);
            self.jPaneApi.getContentPane().append(self.pointStructure(response, field, lat, long));
            self.findDiv(administrativeContainer, '.badge-wrapper').append(self.pointStructure(response, field, lat, long));
            self.jPaneApi.reinitialise();
        });
    },
    findDiv: function (container, className) {
        return container.find(className);
    },
    asyncRequest: function (method, url, data) {
        return $.ajax({
            method: method,
            url: url,
            data: data
        });
    },
    pointClone: function (countryIndex, currentAdministrativeCount) {
        var collection = $('.point-container');
        var proto = collection.html().replace(/locationCount/g, countryIndex);
        proto = proto.replace(/administrativeCount/g, currentAdministrativeCount);

        return proto;
    },
    generateField: function (mapWrapperContainer, administrativeContainer, countryIndex, currentAdministrativeCount, countryId, latLong, marker) {
        if (this.administrativeCount[countryId] === undefined) {
            this.administrativeCount[countryId] = currentAdministrativeCount;
        } else {
            currentAdministrativeCount = this.administrativeCount[countryId] + 1;
            this.administrativeCount[countryId] = currentAdministrativeCount;
        }
        var proto = this.pointClone(countryIndex, currentAdministrativeCount);
        var latId = $(proto).find('.latitude').attr('id');
        var longId = $(proto).find('.longitude').attr('id');
        var locationNameId = $(proto).find('.locationName').attr('id');

        this.setLocationName(mapWrapperContainer, administrativeContainer, locationNameId, latLong.lat, latLong.lng);

        administrativeContainer.append(proto);
        administrativeContainer.find('#' + this.escapeBrackets(latId)).val(latLong.lat);
        administrativeContainer.find('#' + this.escapeBrackets(longId)).val(latLong.lng);
        this.setMarkersAttribute(marker, locationNameId);
    },
    pointStructure: function (locationName, source, lat, long) {
        return "<div class='badge'>" +
            "<div><span class='name'>" + locationName + "</span><span>(" + lat + "," + long + ")</span></div>" +
            "<button class='remove_from_location' id='" + source + "' type='button'>X</button>" +
            "</div>";
    },
    deleteLocation: function (id) {
        var source = $('#' + this.escapeBrackets(id));
        var parent = source.parent();
        var jspPane = '';

        $.each(parent, function (index, div) {
            if ($(div).hasClass('badge') && $(div).parent().hasClass('jspPane')) {
                jspPane = $(div).parent();
            }
        });

        source.remove();
        parent.not('.leaflet-marker-pane, .leaflet-shadow-pane, .badge').parent().remove();
        parent.not('.leaflet-marker-pane, .leaflet-shadow-pane').remove();

        if (jspPane.find('.badge').length === 0) {
            jspPane.append("<p>Click on the map to add locations.</p>");
        } else if (jspPane !== "") {
            jspPane.find('p').remove();
        }
        this.jPaneApi.reinitialise();
    },
    reverseLocation: function () {
        var locations = $('.location').children('.form-group');
        var self = this;
        $.each(locations, function (index, location) {
            var administrativeContainer = $(location).find('.administrative');
            var points = administrativeContainer.find('.point');
            var mapWrapper = $(location).find('.map-wrapper');
            administrativeContainer.append("<div class='badge-wrapper'></div>");
            mapWrapper.append("<div class='badge-wrapper'><p>Click on the map to add locations.</p></div>");

            var pane = mapWrapper.find('.badge-wrapper');
            pane.jScrollPane({autoReinitialise: true});
            self.jPaneApi = pane.data('jsp');

            $.each(points, function (index, point) {
                var latitude = $(point).find('.latitude').val();
                var longitude = $(point).find('.longitude').val();
                var locationName = $(point).find('.locationName').val();
                var field = $(point).find('.locationName').attr('id');
                if (latitude !== "" && longitude !== "") {
                    // console.log(point, latitude, longitude);
                    pane.find('p').remove();
                    if(mapWrapper.find(".close-map").length === 0){
                        mapWrapper.append("<div class='btn close-map'>Done</div>");
                    }
                    $(point).addClass('added');
                    self.jPaneApi.getContentPane().append(self.pointStructure(locationName, field, latitude, longitude));
                    self.findDiv(administrativeContainer, '.badge-wrapper').append(self.pointStructure(locationName, field, latitude, longitude));
                    self.jPaneApi.reinitialise();
                }
            });
        });
    },
    setMarkersAttribute: function (marker, locationNameId) {
        $(marker._icon).attr('id', locationNameId);
        $(marker._shadow).attr('id', locationNameId);
    }
};
