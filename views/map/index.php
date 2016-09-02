<?php

/* @var $this \yii\web\View */
/* @var $states \app\models\State[] */

use yii\helpers\Html;

?>

<section class="content">
    <div id="map" style="width:100%; min-height: 500px; height: auto"></div>
</section>
            

<script type="text/javascript">

    var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
    var window_height = $(window).height();
    $('#map').css('min-height', window_height - neg - 30);
    
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

    function roundd(lat, n) {
        return parseFloat(lat.toFixed(n));
    }

    function correctLat(lat) {
        return roundd(Math.cos(lat*0.0175)*1.025, 4);
    }
    
    function calcCoords(lat, lon) {
        var xFactor = correctLat(lat);
        coords = [
            [lat,lon+0.1], // east
            [lat-0.087*xFactor,lon+0.05], // east-south
            [lat-0.087*xFactor,lon-0.05], // west-south
            [lat,lon-0.1], // west
            [lat+0.087*xFactor,lon-0.05], // west-nord
            [lat+0.087*xFactor,lon+0.05] // east-nord
        ];
        return coords;
    }
    
    function createPolygon(lat, lon) {
        return L.polygon(calcCoords(lat, lon), {
            color: '#000',
            opacity: 0.5,
            fillColor: '#fff',
            fillOpacity: 0.5,
            weight: 1
        }).addTo(map);
    }
    
    var states = {};
    
    <?php foreach($states as $state): ?>
        states[<?=$state->id?>] = L.multiPolygon([<?=$state->polygon?>],{
            color: '#000',
            opacity: 0.5,
            fillColor: '#<?=$state->mapColor?$state->mapColor:'fff'?>',
            fillOpacity: 0.5,
            weight: 1,
            title: 'asd'
        }).bindLabel('<?=Html::encode($state->name)?>');
        states[<?=$state->id?>].addTo(map);
        
    <?php endforeach ?>
    
</script>