<?php

/* @var $this \yii\web\View */
/* @var $list \StdClass[] */

?>

<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Wolrd demography map')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=Yii::t('app', 'Wolrd demography map')?></li>
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
    
    var polygons = {};
    <?php foreach($list as $obj): ?>
        <?php
            $diapason = [
                '0-10 человек на кв.км.',
                '10-30 человек на кв.км.',
                '30-50 человек на кв.км.',
                '50-100 человек на кв.км.',
                '100-300 человек на кв.км.',
                '300-500 человек на кв.км.',
                '500-1000 человек на кв.км.',
                '1000-2000 человек на кв.км.',
                '>2000 человек на кв.км.'
            ][$obj->i];
            $color = [
                '#51CD29',
                '#6BB42A',
                '#849C2A',
                '#948F2C',
                '#AA7B2D',
                '#B4712D',
                '#D1552D',
                '#E6432E',
                '#FB2F2F',
            ][$obj->i];
        ?>
        polygons[<?=$obj->i?>] = L.multiPolygon([<?=json_encode($obj->path)?>],{
            color: '#000',
            opacity: 1,
            fillColor: '<?=$color?>',
            fillOpacity: 0.7,
            weight: 1,
            title: '<?=$diapason?>'
        }).bindLabel('<?=$diapason?>')
        polygons[<?=$obj->i?>].addTo(map);        
    <?php endforeach ?>
            
</script>