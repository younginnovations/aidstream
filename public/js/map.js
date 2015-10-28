/* exported initMap */
/* globals L */

function initMap(elem, latlng) {
    var center = [52.48626, -1.89042];
    if (latlng) {
        center = latlng;
    }
    var map = new L.Map(elem, {
        center: center,
        zoom: 3
    }).addLayer(new L.TileLayer(
            'http://api.tiles.mapbox.com/v3/younginnovations.ijg2d43b/{z}/{x}/{y}.png', {
                attribution: "<a href='https://www.mapbox.com/about/maps/' target='_blank'>&copy; Mapbox &copy; OpenStreetMap</a> | " +
                "<a href='http://www.mapquest.com/' target='_blank' title='Nominatim Search Courtesy of Mapquest'>MapQuest</a>"
            }
        ));

    var icon = L.icon({
        iconUrl: 'images/marker-icon.png',
        shadowUrl: 'images/marker-shadow.png'
    });

    if (latlng) {
        L.marker(latlng, {icon: icon}).addTo(map);
    }

    map.on('click', function (e) {
        clearMarker(elem);
        L.marker(e.latlng).addTo(map);
        populateValues(elem, e.latlng);
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
