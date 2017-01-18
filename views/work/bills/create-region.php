<?php

use yii\widgets\ActiveForm,
    yii\helpers\Html,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\models\politics\bills\BillProto;

/* @var $this yii\base\View */
/* @var $model app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $types array */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'new-bill-form',
    ],
    'action' => Url::to(['work/new-bill']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::CREATE_REGION])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[regionId]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Region'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Region name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Region short name'))?>

<div class="form-group">
    <label><?=Yii::t('app', 'Select instrument:')?></label>
    <div class="btn-group">
        <?=Html::button(Yii::t('app', 'Paint by click'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'paint-click'])?>
        <?=Html::button(Yii::t('app', 'Paint by mouseover'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'paint-over'])?>
        <?=Html::button(Yii::t('app', 'Clear by click'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'clear-click'])?>
        <?=Html::button(Yii::t('app', 'Clear by mouseover'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'clear-over'])?>
    </div>
</div>
<div id="map"></div>
<?=$form->field($model, 'dataArray[tiles]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-regionid',
        'name': 'Bill[dataArray][regionId]',
        'container': '.field-bill-dataarray-regionid',
        'input': '#bill-dataarray-regionid',
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
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
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
        
        var regionId = $('#bill-dataarray-regionid').val();
        get_json('region/tiles', {id: regionId}, function(data){
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
    
    $('#bill-dataarray-regionid').change(loadTiles);
    $(function(){
        loadTiles();
    });
    
</script>
