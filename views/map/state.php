<?php

/* @var $this \yii\web\View */
/* @var $state \app\models\politics\State */

use yii\helpers\Html,
    app\components\LinkCreator;

?>

<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Political map')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=Yii::t('app', 'Political map')?></li>
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active">            
            <a href="#!map/state&id=<?=$state->id?>&mode=3d" class="btn btn-primary btn-xs"><?=Yii::t('app', 'Turn 3D')?></a>
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
    
    var regions = {},
        cities = {};
    
    <?php foreach($state->regions as $region): ?>
        regions[<?=$region->id?>] = L.multiPolygon([<?=$region->polygon?>],{
            color: '#000',
            opacity: 1,
            fillColor: '#<?=$state->mapColor?$state->mapColor:'fff'?>',
            fillOpacity: 0.5,
            weight: 1,
            title: '<?=Html::encode($region->name)?>'
        }).bindLabel('<?=Html::encode($region->name)?>')
        .bindPopup('<?=($region->flag ? Html::img($region->flag, ['style' => 'width:20px']).' ' : '').Html::a(Html::encode($region->name), '/#!region&id='.$region->id)?><br><button class="btn btn-xs btn-primary btn-relocate" data-location-type="region" data-location-id="<?=$region->id?>" ><?=Yii::t('app', 'Relocate here')?></button>');;
        regions[<?=$region->id?>].addTo(map);  
    <?php endforeach ?>
        
    <?php foreach($state->cities as $city): ?>
        cities[<?=$city->id?>] = L.multiPolygon([<?=$city->polygon?>],{
            color: '#000',
            opacity: 1,
            fillColor: '#fff',
            fillOpacity: 0.5,
            weight: 1,
            title: '<?=Html::encode($city->name)?>'
        }).bindLabel('<?=Html::encode($city->name)?>')
        .bindPopup('<?=($city->flag ? Html::img($city->flag, ['style' => 'width:20px']).' ' : '').Html::a(Html::encode($city->name), '/#!city&id='.$city->id)?><br><button class="btn btn-xs btn-primary btn-relocate" data-location-type="city" data-location-id="<?=$city->id?>" ><?=Yii::t('app', 'Relocate here')?></button>');;
        cities[<?=$city->id?>].addTo(map);  
    <?php endforeach ?>
        
    $('.leaflet-popup-pane').on('click', '.btn-relocate', function(){
        var type = $(this).data('locationType'),
            id = $(this).data('locationId');
        
        createAjaxModal('user/relocate-form', {type:type,id:id},
            '<?=Yii::t('app', 'Relocate to <span id="relocate-to-name">{0}</span>', [''])?>',
            '<button class="btn btn-primary" id="btn-set-relocate" disabled="disabled"><?=Yii::t('app', 'Relocate')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>'
        );
    });
    
</script>