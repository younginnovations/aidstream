/* exported initMap */
/* globals L */

function initMap(elem, latlng) {
    var center = [52.48626, -1.89042];
    if (latlng) {
        center = latlng;
    }
    var map = new L.Map(elem, {
        center: center,
        zoom: 6
    }).addLayer(new L.TileLayer(
            'http://api.tiles.mapbox.com/v3/younginnovations.ijg2d43b/{z}/{x}/{y}.png', {
                attribution: "<a href='https://www.mapbox.com/about/maps/' target='_blank'>&copy; Mapbox &copy; OpenStreetMap</a> | " +
                "<a href='http://www.mapquest.com/' target='_blank' title='Nominatim Search Courtesy of Mapquest'>MapQuest</a>"
            }
        ));

    if (latlng) {
        L.marker(latlng).addTo(map);
    }
    map.scrollWheelZoom.disable();

    map.on('click', function (e) {
        clearMarker(elem);
        L.marker(e.latlng).addTo(map);
        populateValues(elem, e.latlng);
    });

    var parentName = elem.replace('[map]', '');
    var lat_id = '#' + parentName + '[latitude]';
    lat_id = lat_id.replace(/([\[\]])/g, '\\$1');

    var lng_id = '#' + parentName + '[longitude]';
    lng_id = lng_id.replace(/([\[\]])/g, '\\$1');

    $(lat_id).keyup(function () {
        changeMap(lat_id, lng_id, elem, map);
    });

    $(lng_id).keyup(function(){
       changeMap(lat_id, lng_id, elem, map);
    });

    return map;
}

function clearMarker(elem) {
    elem = document.getElementById(elem);
    $('.leaflet-marker-pane', elem).html('');
    $('.leaflet-shadow-pane', elem).html('');
}

function populateValues(elem, latLong) {
    var parentName = elem.replace('[map]', '');
    $('input[name="' + parentName + '[latitude]"]').val(latLong.lat);
    $('input[name="' + parentName + '[longitude]"]').val(latLong.lng);
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function changeMap(lat_id, lng_id, elem, map){
    var lat = $(lat_id).val();
    var latNum = isNumber(lat);

    var lng = $(lng_id).val();
    var lngNum = isNumber(lng);

    if(latNum && lngNum){
        clearMarker(elem);
        var latLng = L.latLng(lat, lng);
        map.panTo(latLng);
        L.marker([lat, lng]).addTo(map);
    }
}

function flyTo(map, latLong) {
    map.panTo(latLong);
    map.invalidateSize();
}

