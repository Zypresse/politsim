<?php

/* @var $this \yii\web\View */
/* @var $states \app\models\State[] */

use yii\helpers\Html;

?>

<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Wolrd political map')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=Yii::t('app', 'Wolrd political map')?></li>
        <li class="active">            
            <a href="#!map" class="btn btn-primary btn-xs"><?=Yii::t('app', 'Turn 2D')?></a>
        </li>
    </ol>
</section>
<section class="content">
    <div id="map" style="width:100%; min-height: 500px; height: auto"></div>
</section>
            

<script type="text/javascript">

    var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight() + $('.content-header').outerHeight();
    var window_height = $(window).height();
    $('#map').css('min-height', window_height - neg - 30);
    $('#map').css('height', window_height - neg - 30);
    
    var options = {atmosphere: true, sky: true, center: [45, 34], zoom: 5};
    if (localStorage.getItem('zoom')) {
        options.zoom = localStorage.getItem('zoom');
    }
    if (localStorage.getItem('center-lat') && localStorage.getItem('center-lng')) {
        options.center = [localStorage.getItem('center-lat'),localStorage.getItem('center-lng')];
    }
    
    var earth = new WE.map('map', options);
    
    var Esri_WorldImagery = WE.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            noWrap: true,
            minZoom: 2
    });
    Esri_WorldImagery.addTo(earth);
    
    earth.on('moveend', function(){
        localStorage.setItem('zoom',earth.getZoom());
        localStorage.setItem('center-lat',earth.getCenter().lat);
        localStorage.setItem('center-lng',earth.getCenter().lng);
    });
    
    var states = {};
    
    <?php foreach($states as $state): ?>
        <?php foreach (json_decode($state->polygon) as $polygon): ?>
            states[<?=$state->id?>] = WE.polygon(<?=json_encode($polygon)?>,{
                color: '#000',
                opacity: 1,
                fillColor: '#<?=$state->mapColor?$state->mapColor:'fff'?>',
                fillOpacity: 0.5,
                weight: 1
            });
            states[<?=$state->id?>].addTo(earth);        
        <?php endforeach ?>        
    <?php endforeach ?>
    
</script>