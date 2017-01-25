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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::CHANGE_REGIONS_BORDER])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[region1Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'First region'))?>
<?=$form->field($model, 'dataArray[region2Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Second region'))?>
<div id="same-regions-alert" class="alert alert-warning">
    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
    <h4><i class="icon fa fa-warning"></i> <?=Yii::t('app', 'Alert!')?></h4>
    <?=Yii::t('app', 'Please select two different regions')?>
</div>
<div class="form-group">
    <label><?=Yii::t('app', 'Select instrument:')?></label>
    <div class="btn-group">
        <?=Html::button(Yii::t('app', 'Paint first region by click'), ['class' => 'btn btn-default btn-xs instrument btn-danger active', 'data-instrument' => 'paint-click'])?>
        <?=Html::button(Yii::t('app', 'Paint first region by mouseover'), ['class' => 'btn btn-default btn-xs instrument btn-danger', 'data-instrument' => 'paint-over'])?>
        <?=Html::button(Yii::t('app', 'Paint second region by click'), ['class' => 'btn btn-default btn-xs instrument btn-primary', 'data-instrument' => 'clear-click'])?>
        <?=Html::button(Yii::t('app', 'Paint second region by mouseover'), ['class' => 'btn btn-default btn-xs instrument btn-primary', 'data-instrument' => 'clear-over'])?>
    </div>
</div>
<div id="map"></div>
<?=$form->field($model, 'dataArray[tiles1]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'dataArray[tiles2]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-region1id',
        'name': 'Bill[dataArray][region1Id]',
        'container': '.field-bill-dataarray-region1id',
        'input': '#bill-dataarray-region1id',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-region2id',
        'name': 'Bill[dataArray][region2Id]',
        'container': '.field-bill-dataarray-region2id',
        'input': '#bill-dataarray-region2id',
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
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
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
        
        var region1Id = parseInt($('#bill-dataarray-region1id').val()),
            region2Id = parseInt($('#bill-dataarray-region2id').val());
    
        if (region1Id === region2Id) {
            $('#same-regions-alert').slideDown();
            return false;
        }
        $('#same-regions-alert').slideUp();
    
        get_json('region/tiles', {ids: region1Id+','+region2Id}, function(data){
            var minLat = 180,
                minLon = 180,
                maxLat = -180,
                maxLon = -180;
            for (var i = 0, l = data.result.length; i < l; i++) {
                var tile = data.result[i];
                tile.lat = parseFloat(tile.lat);
                tile.lon = parseFloat(tile.lon);
                tile.regionId = parseInt(tile.regionId);
                
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
                tiles.push(renderTile(tile.id, tile.x, tile.y, tile.coords, tile.regionId === region1Id));
            }
            map.fitBounds([[minLat-1, minLon-1], [maxLat+1, maxLon+1]]);
        });
    }
    
    $('#bill-dataarray-region1id').change(loadTiles);
    $('#bill-dataarray-region2id').change(loadTiles);
    $(function(){
        loadTiles();
    });
    
</script>
