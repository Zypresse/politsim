
var map;

var editionCache = {};
var polygons = [];
var $label;
var instrument = 'paint-on-click';
var currentRequest = false;
var type;
var subType;

function clearCache() {
    editionCache = {};
}

function clearPolygons() {
    while (polygon = polygons.pop()) {
        map.removeLayer(polygon);
    }
}

function reset() {
    clearCache();
    clearPolygons();
    if (map.getZoom() > getMinZoom()) {
        loadPolygons();
    }
}

function getColor() {
    switch (type) {
        case 'land':
            return '#00ee00';
        case 'water':
            return '#00eeff';
        case 'region':
        case 'city':
        default:
            return '#555555';
    }
}

function getMinZoom() {
    switch (type) {
        case 'spectator':
            return 3;
        case 'land':
        case 'water':
            return 5;
        default:
            return 6;
    }
}

function getLabelText(loading) {
    switch (type) {
        case 'spectator':
            return loading ? 'Loading regions...' : 'Zoom in to see regions';
        default:
            return loading ? 'Loading tiles...' : 'Zoom in to see tiles';
    }
}

function getLoadingScript() {
    switch (type) {
        case 'spectator':
            return '/admin/map/regions';
        default:
            return '/admin/map/tiles';
    }
}

function regionClick(e) {
//    console.log(e.target);
    switch (instrument) {
        case 'paint-on-click':
            if (!e.target.occupied) {
                e.target.setStyle({
                    fillOpacity: 0.5,
                    fillColor: getColor()
                });
                e.target.occupied = true;
                editionCache[e.target.id] = true;
            }
            break;
        case 'clear-on-click':
            if (e.target.occupied) {
                e.target.setStyle({
                    fillOpacity: 0.5,
                    fillColor: '#fff'
                });
                e.target.occupied = false;
                editionCache[e.target.id] = false;
            }
            break;
    }
}

function regionMouseOver(e) {

    switch (instrument) {
        case 'paint-on-move':
            if (!e.target.occupied) {
                e.target.setStyle({
                    fillOpacity: 0.5,
                    fillColor: getColor()
                });
                e.target.occupied = true;
                editionCache[e.target.id] = true;
            }
            break;
        case 'clear-on-move':
            if (e.target.occupied) {
                e.target.setStyle({
                    fillOpacity: 0.5,
                    fillColor: '#fff'
                });
                e.target.occupied = false;
                editionCache[e.target.id] = false;
            }
            break;
    }

    if (!L.Browser.ie && !L.Browser.opera) {
        e.target.bringToFront();
    }
}

function regionMouseOut(e) {

    if (e.target.disabled) {
        return;
    }

}

function paintAll() {
    for (var i in polygons) {
        if (!polygons[i].disabled) {
            polygons[i].setStyle({
                fillOpacity: 0.5,
                fillColor: getColor()
            });
            polygons[i].occupied = true;
            editionCache[polygons[i].id] = true;
        }
    }
}

function clearPaintAll() {
    for (var i in polygons) {
        if (!polygons[i].disabled) {
            polygons[i].setStyle({
                fillColor: '#fff',
                fillOpacity: 0
            });
            polygons[i].occupied = false;
            editionCache[polygons[i].id] = false;
        }
    }
}

function onLoadTiles(data) {
    var tile;
    while (tile = data.result.pop()) {

        if (editionCache[tile.id] !== undefined) {
            tile.occupied = editionCache[tile.id];
        }

        var color = tile.occupied ? getColor() : 'white';
        var opacity = tile.occupied ? 0.7 : 0;
        if (tile.disabled) {
            color = 'red';
            opacity = 0.5;
        }
        if (editionCache[tile.id] !== undefined) {
            opacity = 0.5;
        }
        var polygon = L.polygon(tile.coords, {
            weight: 1,
            color: 'black',
            opacity: 0.3,
            fillColor: color,
            fillOpacity: opacity
        });
        polygon.id = tile.id;
        polygon.occupied = tile.occupied;
        polygon.disabled = tile.disabled;
        polygon.on('click', regionClick);
        polygon.on('mouseover', regionMouseOver);
        polygon.on('mouseout', regionMouseOut);
        polygon.addTo(map);
        polygons.push(polygon);
    }
    currentRequest = false;
    $label.hide();
}


function onLoadRegions(data) {
    var region, city;
    var regions = data.result.regions;
    var cities = data.result.cities;
    while (region = regions.pop()) {

        var polygon = L.multiPolygon([region.polygon], {
            weight: 1,
            color: 'black',
            opacity: 0.5,
            fillColor: getRegionColor(parseInt(region.id)),
            fillOpacity: 0.9
        });
        polygon.bindPopup('ID: ' + region.id + ' (' + region.name + ')');
        polygon.addTo(map);
        polygons.push(polygon);
    }
    while (city = cities.pop()) {

        var polygon = L.multiPolygon([city.polygon], {
            weight: 1,
            color: 'black',
            opacity: 0.5,
            fillColor: 'white',
            fillOpacity: 0.9
        });
        polygon.bindPopup('ID: ' + city.id + ' (' + city.name + ')');
        polygon.addTo(map);
        polygons.push(polygon);
    }
    currentRequest = false;
    $label.hide();
}


function loadPolygons() {
    $label.text(getLabelText(true)).show();
    var bounds = map.getBounds();
    if (currentRequest) {
        currentRequest.abort();
    }
    currentRequest = $.getJSON(getLoadingScript(), {
        minLat: bounds.getSouth() - 0.1,
        maxLat: bounds.getNorth() + 0.1,
        minLng: bounds.getWest() - 0.1,
        maxLng: bounds.getEast() + 0.1,
        type: type,
        subType: subType
    }, type === 'spectator' ? onLoadRegions : onLoadTiles);
}

function mapAutoHeight()
{
    var neg = $('#admin-nav').outerHeight() + $('#admin-content-header').outerHeight();
    var window_height = $(window).height();
    $('#map').css('min-height', window_height - neg - 50);
}

function initMap(t, s)
{
    type = t;
    subType = s;
    $label = $('#map-info-label');
    $label.text(getLabelText(false)).show();
    mapAutoHeight();
    
    var options = {center: [45, 34], zoom: 5};
    if (localStorage.getItem('center-lat') && localStorage.getItem('center-lng')) {
        options.center = [localStorage.getItem('center-lat'),localStorage.getItem('center-lng')];
    }
    if (localStorage.getItem('zoom')) {
        options.zoom = localStorage.getItem('zoom');
    }
    if (localStorage.getItem('instrument')) {
        instrument = localStorage.getItem('instrument');
    }
    $('.select-instrument').click(function () {
        instrument = $(this).attr('id');
        localStorage.setItem('instrument', instrument);
    });
    
    map = new L.map('map', options);
    
    
    switch (type) {
        case 'city':
        case 'region':
            
//            var Stamen_TonerHybrid = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner-hybrid/{z}/{x}/{y}.{ext}', {
//                attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
//                subdomains: 'abcd',
//                noWrap: true,
//                minZoom: 2,
//                maxZoom: 18,
//                ext: 'png'
//            });
//            Stamen_TonerHybrid.addTo(map);

            var layer = L.tileLayer(
              'https://vec{s}.maps.yandex.net/tiles?l=map&v=4.55.2&z={z}&x={x}&y={y}&scale=2&lang=ru_RU', {
                subdomains: ['01', '02', '03', '04'],
                attribution: 'Яндекс',
                reuseTiles: true,
                updateWhenIdle: false,
                minZoom: 1,
                maxZoom: 12,
                noWrap: true
              }
            );
            layer.addTo(map);
            map.options.crs = L.CRS.EPSG3395;
            break;
            
        case 'land':
        case 'water':
        default:
//            var layer = L.tileLayer(
//              'https://vec{s}.maps.yandex.net/tiles?l=map&v=4.55.2&z={z}&x={x}&y={y}&scale=2&lang=ru_RU', {
//                subdomains: ['01', '02', '03', '04'],
//                attribution: 'Яндекс',
//                reuseTiles: true,
//                updateWhenIdle: false,
//                minZoom: 1,
//                maxZoom: 12,
//                noWrap: true
//              }
//            );
//            layer.addTo(map);
//            map.options.crs = L.CRS.EPSG3395;

//            var Esri_WorldImagery = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
//                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
//                    noWrap: true,
//                    minZoom: 2
//            });
//            Esri_WorldImagery.addTo(map);
                
            var Stamen_TerrainBackground = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}.{ext}', {
                    attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    subdomains: 'abcd',
                    noWrap: true,
                    minZoom: 2,
                    maxZoom: 18,
                    ext: 'png'
            });
            Stamen_TerrainBackground.addTo(map);
                
            break;
    }
    
    
    map.on('moveend', function(){
        localStorage.setItem('zoom',map.getZoom());
        localStorage.setItem('center-lat',map.getCenter().lat);
        localStorage.setItem('center-lng',map.getCenter().lng);
    });
    map.on("dragstart", function (e) {
        $("#map").addClass("dragging");
        clearPolygons();
    });
    map.on("dragend", function (e) {
        setTimeout(function () {
            $("#map").removeClass("dragging");
        }, 100);
        clearPolygons();
        if (map.getZoom() > getMinZoom()) {
            loadPolygons();
        }
    });

    map.on("zoomstart", function (e) {
        clearPolygons();
    });
    map.on("zoomend", function (e) {
        clearPolygons();
        if (map.getZoom() > getMinZoom()) {
            loadPolygons();
        } else {
            $label.text(getLabelText(false)).show();
        }
    });
    

    if (map.getZoom() > getMinZoom()) {
        loadPolygons();
    }

    if (instrument) {
        $('#' + instrument).prop('checked', true);
    }
}


function saveAll() {
    var data = {
        type: type,
        subType: subType
    };
    var selected = [],
        deleted = [];
    for (var i in editionCache) {
        if (editionCache[i]) {
            selected.push(i);
        } else {
            deleted.push(i);
        }
    }
    data.selected = selected.join(',');
    data.deleted = deleted.join(',');
    $.post('/admin/map/save', data, function (result) {
        alert(result);
    });
}