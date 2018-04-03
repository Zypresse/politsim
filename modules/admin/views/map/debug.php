<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $tiles \app\models\map\Tile[] */

$this->title = Yii::t('app', 'Wolrd debug map');

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
    
    var tiles = {};
    
EOJS;
    
    foreach ($tiles as $tile) {
        $js .= "tiles[{$tile->id}] = L.polygon([".json_encode($tile->coords)."],{
            color: '#fff',
            opacity: 1,
            fillColor: '#f00',
            fillOpacity: 0.9,
            weight: 1,
            title: '{$tile->id}'
        }).bindTooltip('{$tile->id}', {permanent:true});
        tiles[{$tile->id}].addTo(map);";
    }
    
    $this->registerJs($js);
    
