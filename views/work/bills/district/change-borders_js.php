<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-district1id',
        'name': 'Bill[dataArray][district1Id]',
        'container': '.field-bill-dataarray-district1id',
        'input': '#bill-dataarray-district1id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-district2id',
        'name': 'Bill[dataArray][district2Id]',
        'container': '.field-bill-dataarray-district2id',
        'input': '#bill-dataarray-district2id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tiles1',
        'name': 'Bill[dataArray][tiles1]',
        'container': '.field-bill-dataarray-tiles1',
        'input': '#bill-dataarray-tiles1',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tiles2',
        'name': 'Bill[dataArray][tiles2]',
        'container': '.field-bill-dataarray-tiles2',
        'input': '#bill-dataarray-tiles2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
            
    $('#work-new-bill-form-modal .modal-dialog').addClass('modal-lg');
    
    var currentInstrument = 'paint-click';
    
    $('.instrument').click(function(){
        var $this = $(this);
        currentInstrument = $this.data('instrument');
        $('.instrument.active').removeClass('active');
        $this.addClass('active');
    });
    
    $('#map').css('height',$('#work-new-bill-form-modal .modal-dialog').height()-30);
    var map = new L.map('map', {
        crs: L.CRS.EPSG3395,
        center: [45, 34],
        zoom: 5
    });
//    map.invalidateSize();
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
    ).addTo(map);
//    var osm = new L.TileLayer(
//        'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//            minZoom: 1,
//            maxZoom: 12,
//            noWrap: true,
//            attribution: 'Map data © OpenStreetMap contributors'
//        }
//    );
//    osm.addTo(map);

    function clickFeature(e) {

        switch (currentInstrument) {
            case 'paint-click':
                if (!e.target.isSelected) {
                    e.target.setSelected(true);
                }
                break;
            case 'clear-click':
                if (e.target.isSelected) {
                    e.target.setSelected(false);
                }
                break;
        }

    }
    
    function highlightFeature(e) {
        e.target.setStyle({
            fillColor: (currentInstrument === 'paint-over' || currentInstrument === 'paint-click') ? 'red' : 'blue',
            fillOpacity: 0.8
        });
        
        if (currentInstrument === 'paint-over' && !e.target.isSelected) {
            e.target.setSelected(true);
        }
        if (currentInstrument === 'clear-over' && e.target.isSelected) {
            e.target.setSelected(false);
        }
    }

    function resetHighlight(e) {
        e.target.setStyle({
            fillColor: e.target.isSelected ? 'red' : 'blue',
            fillOpacity: e.target.isSelected !== e.target.isSelectedDefault ? 0.7 : 0.3
        });
    }
    
    function updateSelectedTiles() {
        var selectedIds = [],
            unselectedIds = [];
        for (var i = 0, l = tiles.length; i < l; i++) {
            var tile = tiles[i];
            if (tile.isSelected) {
                selectedIds.push(tile.name);
            } else {
                unselectedIds.push(tile.name);
            }
        }
        $('#bill-dataarray-tiles1').val(selectedIds.join(','));
        $('#bill-dataarray-tiles2').val(unselectedIds.join(','));
    }
        
    function renderTile(id, x, y, coords, isSelected) {
        
        var pol = L.polygon(coords, {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: isSelected ? 'red' : 'blue',
            fillOpacity: 0.3
        }).addTo(map);
        pol.on('click', clickFeature);
        pol.on('mouseover', highlightFeature);
        pol.on('mouseout', resetHighlight);
        pol.name = id;
        pol.x = x;
        pol.y = y;
        pol.isSelected = isSelected;
        pol.isSelectedDefault = isSelected;
        pol.setSelected = function (value) {
            this.isSelected = value;
            this.setStyle({
                fillColor: value ? 'red' : 'blue',
                fillOpacity: this.isSelectedDefault !== value ? 0.7 : 0.3
            });
            updateSelectedTiles();
        };
//        pol.getCentroid = function() {
//            var bounds = this.getBounds();
//            var ne = bounds._northEast;
//            var sw = bounds._southWest;
//
//            return {
//                'lat': (ne.lat+sw.lat)/2,
//                'lng': (ne.lng+sw.lng)/2
//            };
//        }
        return pol;
    }
    
    var tiles = [];

    function clearTiles() {
        while (tiles.length > 0) {
            var tile = tiles.pop();
            tile._container.remove();
        }
    }

    function loadTiles() {
        
        clearTiles();
        
        var district1Id = parseInt($('#bill-dataarray-district1id').val()),
            district2Id = parseInt($('#bill-dataarray-district2id').val());
    
        if (district1Id === district2Id) {
            $('#same-regions-alert').slideDown();
            return false;
        }
        $('#same-regions-alert').slideUp();
    
        get_json('district/tiles', {ids: district1Id+','+district2Id}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0, l = data.result.length; i < l; i++) {
                var tile = data.result[i];
                tile.lat = parseFloat(tile.lat);
                tile.lon = parseFloat(tile.lon);
                tile.districtId = parseInt(tile.districtId);
                
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
                tiles.push(renderTile(tile.id, tile.x, tile.y, tile.coords, tile.districtId === district1Id));
            }
            map.fitBounds([[minLat-1, minLon-1], [maxLat+1, maxLon+1]]);
        });
    }
    
    $('#bill-dataarray-district1id').change(loadTiles);
    $('#bill-dataarray-district2id').change(loadTiles);
    $(function(){
        loadTiles();
    });
    
</script>
