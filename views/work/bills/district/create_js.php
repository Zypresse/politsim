<?php

/* @var $this yii\base\View */

?>
<script type="text/javascript">
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-districtid',
        'name': 'Bill[dataArray][districtId]',
        'container': '.field-bill-dataarray-districtid',
        'input': '#bill-dataarray-districtid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-name',
        'name': 'Bill[dataArray][name]',
        'container': '.field-bill-dataarray-name',
        'input': '#bill-dataarray-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-nameshort',
        'name': 'Bill[dataArray][nameShort]',
        'container': '.field-bill-dataarray-nameshort',
        'input': '#bill-dataarray-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tiles',
        'name': 'Bill[dataArray][tiles]',
        'container': '.field-bill-dataarray-tiles',
        'input': '#bill-dataarray-tiles',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    var currentInstrument = 'paint-click';
    
    $('.instrument').click(function(){
        var $this = $(this);
        currentInstrument = $this.data('instrument');
        $('.instrument.active').removeClass('active');
        $this.addClass('active');
    });
    
    $('#work-new-bill-form-modal .modal-dialog').addClass('modal-lg');
    
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

    function highlightFeature(e) {
        e.target.setStyle({
            fillOpacity: 0.6
        });
        
        if (currentInstrument === 'paint-over' && !e.target.isSelected) {
            e.target.setSelected(true);
        }
        if (currentInstrument === 'clear-over' && e.target.isSelected) {
            e.target.setSelected(false);
        }
    }

    function resetHighlight(e) {
        if (!e.target.isSelected) {
            e.target.setStyle({
                fillOpacity: 0.3
            });
        }
    }
    
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
    
    function updateSelectedTiles() {
        var selectedIds = [];
        for (var i = 0, l = tiles.length; i < l; i++) {
            var tile = tiles[i];
            if (tile.isSelected) {
                selectedIds.push(tile.name);
            }
        }
        $('#bill-dataarray-tiles').val(selectedIds.join(','));
    }
    
    function renderTile(id, x, y, coords) {
        
        var pol = L.polygon(coords, {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: 'gray',
            fillOpacity: 0.3
        }).addTo(map);
        pol.on('click', clickFeature);
        pol.on('mouseover', highlightFeature);
        pol.on('mouseout', resetHighlight);
        pol.name = id;
        pol.x = x;
        pol.y = y;
        pol.isSelected = false;
        pol.setSelected = function (value) {
            this.isSelected = value;
            this.setStyle({
                fillColor: value ? 'green' : 'gray',
                fillOpacity: value ? 0.7 : 0.3
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
        
        var districtId = $('#bill-dataarray-districtid').val();
        get_json('district/tiles', {id: districtId}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0, l = data.result.length; i < l; i++) {
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
                tiles.push(renderTile(tile.id, tile.x, tile.y, tile.coords));
            }
            map.fitBounds([[minLat-1, minLon-1], [maxLat+1, maxLon+1]]);
        });
    }
    
    $('#bill-dataarray-districtid').change(loadTiles);
    $(function(){
        loadTiles();
    });
    
</script>
