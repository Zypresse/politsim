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
    map = new L.map('map', options);
    
    var Stamen_TerrainBackground = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}.{ext}', {
	attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	subdomains: 'abcd',
	noWrap: true,
        minZoom: 2,
	maxZoom: 18,
	ext: 'png'
    });
    Stamen_TerrainBackground.addTo(map);
    
    map.on('moveend', function(){
        localStorage.setItem('zoom',map.getZoom());
        localStorage.setItem('center-lat',map.getCenter().lat);
        localStorage.setItem('center-lng',map.getCenter().lng);
    });
    
    states = {};
    
EOJS;
    
    foreach ($states as $state) {
        if (!$state->polygon) {
            continue;
        }
        $js .= "states[{$state->id}] = L.polygon([".json_encode($state->polygon->data)."],{
            color: '#000',
            opacity: 1,
            fillColor: '#{$state->mapColor}',
            fillOpacity: 0.5,
            weight: 1,
            title: '{$state->name}'
        }).bindTooltip('{$state->tooltipName}', {permanent: true, className: 'map-state-tooltip', offset: [0, 0], direction: 'center' });
        states[{$state->id}].addTo(map);
        
        ";
        unset($state->polygon);
    }
    
    $this->registerJs($js);
    
