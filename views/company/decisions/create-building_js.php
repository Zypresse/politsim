<?php

use app\models\economics\units\BuildingProto;

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $('#company-new-decision-list-form-modal .modal-dialog').addClass('modal-lg');
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
    
    function onRegionChange() {
        clearTiles();
        get_json('region/tiles', {id: $('#selected-region-id').val()}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0; i < data.result.lenth; i++) {
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
        });
    }
    
    function clearTiles() {
        while (tiles.length > 0) {
            var tile = tiles.pop();
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
    
    var buildingIcon = L.icon({
        iconUrl: '/img/building.png',
        iconRetinaUrl: '/img/building@2x.png',
        iconSize: [32, 32]
    });
    
    function clickFeatureBuild(e) {
        if (!e.target.isDisabled) {
            if (buildMarker === null) {
                buildMarker = L.marker(e.target.getCentroid(), {icon: homeIcon});
                buildMarker.addTo(buildMap);
            } else {
                buildMarker.setLatLng(e.target.getCentroid());
            }
            $('#companydecision-dataarray-tileid').val(e.target.name);
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
    
    function onProtoChange(){
        var id = parseInt($('#companydecision-dataarray-protoid').val());
        isOnlyCities = false;
        switch (id) {
            case <?= BuildingProto::OFFICE ?>:
                isOnlyCities = true;
                break;
        }
    }
    
    $('#selected-state-id').change(onStateChange);
    $('#selected-region-id').change(onRegionChange);
    $('#companydecision-dataarray-protoid').change(onProtoChange);
    
    $(function(){
        onRegionChange();
        onProtoChange();
    });
    
    setTimeout(function(){
        buildMap.invalidateSize();
        buildMap.fitBounds([[minLat-0.1, minLon-0.1], [maxLat+0.1, maxLon+0.1]]);
    },300);
    
</script>
