<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-district1id',
        'name': 'Bill[dataArray][district1Id]',
        'container': '.field-bill-dataarray-district1id',
        'input': '#bill-dataarray-district1id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-district2id',
        'name': 'Bill[dataArray][district2Id]',
        'container': '.field-bill-dataarray-district2id',
        'input': '#bill-dataarray-district2id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
            
    $('#work-new-bill-form-modal .modal-dialog').addClass('modal-lg');
    
    $('#map').css('height',$('#work-new-bill-form-modal .modal-dialog').height()-30);
    var map = new L.map('map', {
        crs: L.CRS.EPSG3395,
        center: [45, 34],
        zoom: 5
    });
//    map.invalidateSize();
    L.tileLayer(
        'http://vec{s}.maps.yandex.net/tiles?l=map&v=4.55.2&z={z}&x={x}&y={y}&scale=2&lang=ru_RU', {
            subdomains: ['01', '02', '03', '04'],
            attribution: 'Яндекс',
            reuseTiles: true,
            updateWhenIdle: false,
            minZoom: 1,
            maxZoom: 12,
            noWrap: true
        }
    ).addTo(map);
//    var osm = new L.TileLayer(
//        'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//            minZoom: 1,
//            maxZoom: 12,
//            noWrap: true,
//            attribution: 'Map data © OpenStreetMap contributors'
//        }
//    );
//    osm.addTo(map);

    function highlightFeature(e) {
        e.target.setStyle({
            fillOpacity: 0.6
        });
    }

    function resetHighlight(e) {
        e.target.setStyle({
            fillOpacity: 0.3
        });
    }
        
    function renderRegion(coords) {
        
        var pol = L.multiPolygon([coords], {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: 'gray',
            fillOpacity: 0.3
        }).addTo(map);
        pol.on('mouseover', highlightFeature);
        pol.on('mouseout', resetHighlight);
//        pol.getCentroid = function() {
//            var bounds = this.getBounds();
//            var ne = bounds._northEast;
//            var sw = bounds._southWest;
//
//            return {
//                'lat': (ne.lat+sw.lat)/2,
//                'lng': (ne.lng+sw.lng)/2
//            };
//        }
        return pol;
    }
    
    var regions = [];

    function clearRegions() {
        while (regions.length > 0) {
            var region = regions.pop();
            for (var i in region._layers) {
                region._layers[i]._container.remove();
            }
        }
    }

    function loadRegions() {
        
        clearRegions();
        
        var district1Id = $('#bill-dataarray-district1id').val(),
            district2Id = $('#bill-dataarray-district2id').val();
        
        if (district1Id === district2Id) {
            $('#same-regions-alert').slideDown();
            return false;
        }
        $('#same-regions-alert').slideUp();
    
        get_json('district/polygons', {ids: district1Id+','+district2Id}, function(data){
            
            for (var i = 0, l = data.result.length; i < l; i++) {
                var region = data.result[i];
                regions.push(renderRegion(region.coords));
            }
            
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0; i < regions.length; i++) {
                var bounds = regions[i].getBounds();
                if (bounds._northEast.lat > maxLat) {
                    maxLat = bounds._northEast.lat;
                }
                if (bounds._southWest.lat < minLat) {
                    minLat = bounds._southWest.lat;
                }
                if (bounds._northEast.lng < minLon) {
                    minLon = bounds._northEast.lng;
                }
                if (bounds._southWest.lng > maxLon) {
                    maxLon = bounds._southWest.lng;
                }
            }
            map.fitBounds([[minLat, minLon], [maxLat, maxLon]]);
        });
    }
    
    $('#bill-dataarray-district1id').change(loadRegions);
    $('#bill-dataarray-district2id').change(loadRegions);
    $(function(){
        loadRegions();
    });
    
</script>
