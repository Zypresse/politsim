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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::IMPLODE_REGIONS])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[region1Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Main region'))?>
<?=$form->field($model, 'dataArray[region2Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Children region'))?>
<div id="same-regions-alert" class="alert alert-warning">
    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
    <h4><i class="icon fa fa-warning"></i> <?=Yii::t('app', 'Alert!')?></h4>
    <?=Yii::t('app', 'Please select two different regions')?>
</div>
<div id="map"></div>

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
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
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
    }

    function resetHighlight(e) {
        e.target.setStyle({
            fillOpacity: 0.3
        });
    }
        
    function renderRegion(coords) {
        
        var pol = L.multiPolygon([coords], {
            color: '#000',
            opacity: 0.5,
            weight: 1,
            fillColor: 'gray',
            fillOpacity: 0.3
        }).addTo(map);
        pol.on('mouseover', highlightFeature);
        pol.on('mouseout', resetHighlight);
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
    
    var regions = [];

    function clearRegions() {
        while (regions.length > 0) {
            var region = regions.pop();
            for (var i in region._layers) {
                region._layers[i]._container.remove();
            }
        }
    }

    function loadRegions() {
        
        clearRegions();
        
        var region1Id = $('#bill-dataarray-region1id').val(),
            region2Id = $('#bill-dataarray-region2id').val();
        
        if (region1Id === region2Id) {
            $('#same-regions-alert').slideDown();
            return false;
        }
        $('#same-regions-alert').slideUp();
    
        get_json('region/polygons', {ids: region1Id+','+region2Id}, function(data){
//            var minLat = 180,
//                minLon = 180,
//                maxLat = -180,
//                maxLon = -180;
            for (var i = 0, l = data.result.length; i < l; i++) {
                var region = data.result[i];
//                tile.lat = parseFloat(tile.lat);
//                tile.lon = parseFloat(tile.lon);
//                
//                if (tile.lat < minLat) {
//                    minLat = tile.lat;
//                }
//                if (tile.lon < minLon) {
//                    minLon = tile.lon;
//                }
//                if (tile.lat > maxLat) {
//                    maxLat = tile.lat;
//                }
//                if (tile.lon > maxLon) {
//                    maxLon = tile.lon;
//                }
                regions.push(renderRegion(region.coords));
            }
//            map.fitBounds([[minLat-1, minLon-1], [maxLat+1, maxLon+1]]);
        });
    }
    
    $('#bill-dataarray-region1id').change(loadRegions);
    $('#bill-dataarray-region2id').change(loadRegions);
    $(function(){
        loadRegions();
    });
    
</script>