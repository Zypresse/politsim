<?php

/* @var $this \yii\web\View */
/* @var $states \app\models\politics\State[] */

use yii\helpers\Html;

?>

<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Wolrd political map')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=Yii::t('app', 'Wolrd political map')?></li>
        <li class="active">            
            <a href="#!map&mode=3d" class="btn btn-primary btn-xs"><?=Yii::t('app', 'Turn 3D')?></a>
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
    
    var states = {};
    
    <?php foreach($states as $state): ?>
        states[<?=$state->id?>] = L.multiPolygon([<?=$state->polygon?>],{
            color: '#000',
            opacity: 1,
            fillColor: '#<?=$state->mapColor?$state->mapColor:'fff'?>',
            fillOpacity: 0.5,
            weight: 1,
            title: '<?=Html::encode($state->name)?>'
        }).bindLabel('<?=Html::encode($state->name)?>')
        .bindPopup('<?=Html::img($state->flag, ['style' => 'width:20px']).' '.Html::a(Html::encode($state->name), '/#!state&id='.$state->id)?>');
        states[<?=$state->id?>].addTo(map);        
    <?php endforeach ?>
            
</script>