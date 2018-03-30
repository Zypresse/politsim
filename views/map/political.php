<?php

/* @var $this \yii\web\View */
/* @var $states \app\models\government\State[] */

$this->title = Yii::t('app', 'Wolrd political map');

?>
<div id="map" style="width:100%; min-height: 500px; height: auto"></div>
<?php

    $js = <<<EOJS
            
    $('#map').css('min-height', $('#bigcontainer').height());
    
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
    
EOJS;
    
    foreach ($states as $state) {
        if (!$state->polygon) {
            continue;
        }
        $js .= "states[{$state->id}] = L.multiPolygon([".json_encode($state->polygon->data)."],{
            color: '#000',
            opacity: 1,
            fillColor: '#{$state->mapColor}',
            fillOpacity: 0.5,
            weight: 1,
            title: '{$state->name}'
        }).bindLabel('{$state->name}');
        states[{$state->id}].addTo(map);";
        unset($state->polygon);
    }
    
    $this->registerJs($js);
    
