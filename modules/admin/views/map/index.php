<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $cities \app\models\map\City[] */
/* @var $regions \app\models\map\Region[] */

$this->title = Yii::t('app', 'Wolrd political map');

?>
<div id="map" style="width:100%; min-height: 500px; height: auto"></div>
<?php

    $js = <<<EOJS
    var neg = $('#admin-nav').outerHeight() + $('#admin-content-header').outerHeight();
    var window_height = $(window).height();
    $('#map').css('min-height', window_height - neg - 50);
    
    var options = {center: [45, 34], zoom: 5};
    if (localStorage.getItem('zoom')) {
        options.zoom = localStorage.getItem('zoom');
    }
    if (localStorage.getItem('center-lat') && localStorage.getItem('center-lng')) {
        options.center = [localStorage.getItem('center-lat'),localStorage.getItem('center-lng')];
    }
    var map = new L.map('map', options);
    
    var Esri_WorldImagery = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            noWrap: true,
            minZoom: 2
    });
    Esri_WorldImagery.addTo(map);
    
    map.on('moveend', function(){
        localStorage.setItem('zoom',map.getZoom());
        localStorage.setItem('center-lat',map.getCenter().lat);
        localStorage.setItem('center-lng',map.getCenter().lng);
    });
    
    var regions = {};
    var cities = {};
    
EOJS;
    
    foreach ($regions as $region) {
        if (!$region->polygon) {
            continue;
        }
        $js .= "regions[{$region->id}] = L.multiPolygon([".json_encode($region->polygon->data)."],{
            color: '#000',
            opacity: 1,
            fillColor: '#fff',
            fillOpacity: 0.5,
            weight: 1,
            title: '{$region->name}'
        }).bindLabel('{$region->name}');
        regions[{$region->id}].addTo(map);";
        unset($region->polygon);
    }
    
    foreach ($cities as $city) {
        if (!$city->polygon) {
            continue;
        }
        $js .= "cities[{$city->id}] = L.multiPolygon([".json_encode($city->polygon->data)."],{
            color: '#000',
            opacity: 1,
            fillColor: '#0ff',
            fillOpacity: 1,
            weight: 1,
            title: '{$city->name}'
        }).bindLabel('{$city->name}');
        cities[{$city->id}].addTo(map);";
        unset($city->polygon);
    }
    
    $this->registerJs($js);
    
