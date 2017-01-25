<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $region1 \app\models\politics\Region */
/* @var $region2 \app\models\politics\Region */

?>
<p>
<?=Yii::t('app/bills', 'Include region {0} to region {1}', [
    LinkCreator::regionLink($region2),
    LinkCreator::regionLink($region1),
])?>
</p>
<div id="map"></div>
<script type="text/javascript">
    
    $('#map').css('height',300);
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
    
    var polygons = [];
    
    polygons.push(L.multiPolygon([<?=$region1->polygon?>], {
        color: '#000',
        opacity: 0.5,
        weight: 1,
        fillColor: 'blue',
        fillOpacity: 0.3
    }).addTo(map).bindLabel('<?=Html::encode($region1->name)?>'));
    polygons.push(L.multiPolygon([<?=$region2->polygon?>], {
        color: '#000',
        opacity: 0.5,
        weight: 1,
        fillColor: 'red',
        fillOpacity: 0.3
    }).addTo(map).bindLabel('<?=Yii::t('app/bills', '{0} will be imploded into {1} and removed!', [Html::encode($region2->name), Html::encode($region1->name)])?>'));
    
    var minLat = 180,
        minLon = 180,
        maxLat = -180,
        maxLon = -180;
    for (var i = 0; i < polygons.length; i++) {
        var bounds = polygons[i].getBounds();
        if (bounds._northEast.lat > maxLat) {
            maxLat = bounds._northEast.lat;
        }
        if (bounds._southWest.lat < minLat) {
            minLat = bounds._southWest.lat;
        }
        console.log(minLon,maxLon,bounds._northEast.lon,bounds._southWest.lon);
        if (bounds._northEast.lng < minLon) {
            minLon = bounds._northEast.lng;
        }
        if (bounds._southWest.lng > maxLon) {
            maxLon = bounds._southWest.lng;
        }
    }
    
    map.fitBounds([[minLat, minLon], [maxLat, maxLon]]);
    
</script>
