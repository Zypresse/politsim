<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $object \app\models\politics\Region|\app\models\politics\City */

?>
<div id="relocate-map"></div>
<input type="hidden" id="relocate-tile-id" value="">
<script type="text/javascript">
    
    $('#relocate-to-name').html('<?=LinkCreator::link($object)?>');
    
    $('#relocate-map').css('min-height', 300);
    var mapR = new L.map('relocate-map', {
        crs: L.CRS.EPSG3395,
        center: [45, 34],
        zoom: 5
    });
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
    ).addTo(mapR);
    
    var relocateMarker = null;
    var homeIcon = L.icon({
        iconUrl: '/img/home.png',
        iconRetinaUrl: '/img/home@2x.png',
        iconSize: [32, 32]
    });
    
    function clickFeatureRelocate(e) {
        if (relocateMarker === null) {
            relocateMarker = L.marker(e.target.getCentroid(), {icon: homeIcon});
            relocateMarker.addTo(mapR);
        } else {
            relocateMarker.setLatLng(e.target.getCentroid());
        }
        $('#relocate-tile-id').val(e.target.name);
        $('#btn-set-relocate').removeAttr('disabled');
    }
    
    function renderTile(id, x, y, coords, color) {
        
        var pol = L.polygon(coords, {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: color,
            fillOpacity: 0.2
        }).addTo(mapR);
        pol.on('click', clickFeatureRelocate);
//        pol.on('mouseover', highlightFeature);
//        pol.on('mouseout', resetHighlight);
        pol.name = id;
        pol.x = x;
        pol.y = y;
        pol.getCentroid = function() {
            var bounds = this.getBounds();
            var ne = bounds._northEast;
            var sw = bounds._southWest;

            return {
                lat: (ne.lat+sw.lat)/2,
                lng: (ne.lng+sw.lng)/2
            };
        };
        return pol;
    }
    
    var tiles = [];
    var minLat = 180,
        minLon = 180,
        maxLat = -180,
        maxLon = -180;
    <?php foreach ($object->tiles as $tile): ?>
        if (<?=$tile->lat?> < minLat) {
            minLat = <?=$tile->lat?>;
        }
        if (<?=$tile->lon?> < minLon) {
            minLon = <?=$tile->lon?>;
        }
        if (<?=$tile->lat?> > maxLat) {
            maxLat = <?=$tile->lat?>;
        }
        if (<?=$tile->lon?> > maxLon) {
            maxLon = <?=$tile->lon?>;
        }
        tiles.push(renderTile(<?=$tile->id?>, <?=$tile->x?>, <?=$tile->y?>, <?=json_encode($tile->coords)?>, '<?=$tile->cityId ? '#555': '333'?>'));
    <?php endforeach ?>
    
    setTimeout(function(){
        mapR.invalidateSize();
        mapR.fitBounds([[minLat-0.1, minLon-0.1], [maxLat+0.1, maxLon+0.1]]);
    },500);
    
    
    $('#btn-set-relocate').click(function(){
        var tileId = $('#relocate-tile-id').val();
        if (tileId) {
            json_request('user/relocate', {
                tileId: tileId
            }, true, false, function(data) {
                load_page('user/profile');
            }, 'POST');
        }
    });
</script>
