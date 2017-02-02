<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $('#company-new-decision-list-form-modal .modal-dialog').addClass('modal-lg');
    $('#company-new-decision-list-form-modal-label').append(' — <?= Yii::t('app', 'Create new line')?>');
    $('#build-map').css('height', 300);
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-protoid',
        'name': 'CompanyDecision[dataArray][protoId]',
        'container': '.field-companydecision-dataarray-protoid',
        'input': '#companydecision-dataarray-protoid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-tileid',
        'name': 'CompanyDecision[dataArray][tileId]',
        'container': '.field-companydecision-dataarray-tileid',
        'input': '#companydecision-dataarray-tileid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-tile2id',
        'name': 'CompanyDecision[dataArray][tile2Id]',
        'container': '.field-companydecision-dataarray-tile2id',
        'input': '#companydecision-dataarray-tile2id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-name',
        'name': 'CompanyDecision[dataArray][name]',
        'container': '.field-companydecision-dataarray-name',
        'input': '#companydecision-dataarray-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-nameshort',
        'name': 'CompanyDecision[dataArray][nameShort]',
        'container': '.field-companydecision-dataarray-nameshort',
        'input': '#companydecision-dataarray-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'companydecision-dataarray-size',
        'name': 'CompanyDecision[dataArray][size]',
        'container': '.field-companydecision-dataarray-size',
        'input': '#companydecision-dataarray-size',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    var isOnlyCities = false;
        
    function onStateChange() {
        clearTiles();
        get_json('state/regions', {id: $('#selected-state-id').val()}, function(data){
            var $regionSelect = $('#selected-region-id');
            $regionSelect.empty();
            for (var i = 0; i < data.result.length; i++) {
                var option = $('<option>');
                option.setAttr('value', data.result[i].id);
                option.text(data.result[i].name);
                option.appendTo($regionSelect);
            }
        });
    }
    
    function onState2Change() {
        clearTiles2();
        get_json('state/regions', {id: $('#selected-state2-id').val()}, function(data){
            var $regionSelect = $('#selected-region2-id');
            $regionSelect.empty();
            for (var i = 0; i < data.result.length; i++) {
                var option = $('<option>');
                option.setAttr('value', data.result[i].id);
                option.text(data.result[i].name);
                option.appendTo($regionSelect);
            }
        });
    }
    
    function onRegionChange() {
        clearTiles();
        get_json('region/tiles', {id: $('#selected-region-id').val()}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0; i < data.result.length; i++) {
                var tile = data.result[i];
                tile.lat = parseFloat(tile.lat);
                tile.lon = parseFloat(tile.lon);
                
                if (tile.lat < minLat) {
                    minLat = tile.lat;
                }
                if (tile.lon < minLon) {
                    minLon = tile.lon;
                }
                if (tile.lat > maxLat) {
                    maxLat = tile.lat;
                }
                if (tile.lon > maxLon) {
                    maxLon = tile.lon;
                }
                tiles.push(renderTile(tile.id, tile.x, tile.y, tile.coords, isOnlyCities && !tile.cityId));
            }
            buildMap.fitBounds([[minLat-0.1, minLon-0.1], [maxLat+0.1, maxLon+0.1]]);
        });
    }
    
    function onRegion2Change() {
        clearTiles2();
        get_json('region/tiles', {id: $('#selected-region2-id').val()}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0; i < data.result.length; i++) {
                var tile = data.result[i];
                tile.lat = parseFloat(tile.lat);
                tile.lon = parseFloat(tile.lon);
                
                if (tile.lat < minLat) {
                    minLat = tile.lat;
                }
                if (tile.lon < minLon) {
                    minLon = tile.lon;
                }
                if (tile.lat > maxLat) {
                    maxLat = tile.lat;
                }
                if (tile.lon > maxLon) {
                    maxLon = tile.lon;
                }
                tiles2.push(renderTile(tile.id, tile.x, tile.y, tile.coords, isOnlyCities && !tile.cityId));
            }
            buildMap.fitBounds([[minLat-0.1, minLon-0.1], [maxLat+0.1, maxLon+0.1]]);
        });
    }
    
    function clearTiles() {
        while (tiles.length > 0) {
            var tile = tiles.pop();
            tile._container.remove();
        }
    }
    
    function clearTiles2() {
        while (tiles2.length > 0) {
            var tile = tiles2.pop();
            tile._container.remove();
        }
    }
    
    var buildMap = new L.map('build-map', {
        crs: L.CRS.EPSG3395,
        center: [45, 34],
        zoom: 5
    });
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
    ).addTo(buildMap);
    
    var buildMarker = null;
    var buildMarker2 = null;
    var line = null;
    
    var buildingIcon = L.icon({
        iconUrl: '/img/office-building-icon.png',
        iconRetinaUrl: '/img/office-building-icon@2x.png',
        iconSize: [32, 32]
    });
    
    var selectSecondTile = false;
    
    function clickFeatureBuild(e) {
        if (!e.target.isDisabled) {
            if (selectSecondTile) {
                if (buildMarker2 === null) {
                    buildMarker2 = L.marker(e.target.getCentroid(), {icon: buildingIcon});
                    buildMarker2.addTo(buildMap);
                } else {
                    buildMarker2.setLatLng(e.target.getCentroid());
                }
                $('#companydecision-dataarray-tile2id').val(e.target.name);
                selectSecondTile = false;
                line = L.polyline([buildMarker.getLatLng(), buildMarker2.getLatLng()], {color: 'black', width: '5px'});
                line.addTo(buildMap);
            } else {
                if (buildMarker === null) {
                    buildMarker = L.marker(e.target.getCentroid(), {icon: buildingIcon});
                    buildMarker.addTo(buildMap);
                } else {
                    buildMarker.setLatLng(e.target.getCentroid());
                }
                $('#companydecision-dataarray-tileid').val(e.target.name);
                selectSecondTile = true;
                if (line !== null) {
                    line._container.remove();
                    line = null;
                }
            }
        }
    }
    
    function renderTile(id, x, y, coords, isDisabled) {
        
        var pol = L.polygon(coords, {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: isDisabled ? 'red' : 'gray',
            fillOpacity: 0.2
        }).addTo(buildMap);
        pol.on('click', clickFeatureBuild);
        pol.name = id;
        pol.x = x;
        pol.y = y;
        pol.isDisabled = isDisabled;
        pol.getCentroid = function() {
            var bounds = this.getBounds(),
                ne = bounds._northEast,
                sw = bounds._southWest;

            return {
                lat: (ne.lat+sw.lat)/2,
                lng: (ne.lng+sw.lng)/2
            };
        };
        return pol;
    }
    
    var tiles = [];
    var tiles2 = [];
    
    function onProtoChange(){
        isOnlyCities = false;
        onSizeChange();
    }
    
    function onSizeChange()
    {
        var id = parseInt($('#companydecision-dataarray-protoid').val()),
            size = parseInt($('#companydecision-dataarray-size').val());
        $('#future-building-info').empty();
        get_html('building-twotiled/future-info', {protoId:id, size:size}, function(data){
            $('#future-building-info').html(data);
        });
    }
    
    $('#selected-state-id').change(onStateChange);
    $('#selected-state2-id').change(onState2Change);
    $('#selected-region-id').change(onRegionChange);
    $('#selected-region2-id').change(onRegion2Change);
    $('#companydecision-dataarray-protoid').change(onProtoChange);
    $('#companydecision-dataarray-size').change(onSizeChange);
    
    $(function(){
        onRegionChange();
        onRegion2Change();
        onProtoChange();
    });
    
    setTimeout(function(){
        buildMap.invalidateSize();
    },300);
    
</script>
