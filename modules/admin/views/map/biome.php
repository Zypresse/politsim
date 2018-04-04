<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $type string */

$this->registerJsFile("/js/admin/map.js");
$this->registerJs("initMap('{$type}')");

?>

<div class="biome-editor row">
    <div class="col-lg-3">
        <div class="form-group" id="instruments-list" >
            <label for="instrument">Малярня</label>
            <div>
                <label><input type="radio" name="instrument" id="paint-on-move" class="select-instrument" >Красить по наведению</label>
                <label><input type="radio" name="instrument" id="bigpaint-on-move" class="select-instrument" >Красить по наведению [x2]</label>
                <label><input type="radio" name="instrument" id="paint-on-click" class="select-instrument" >Рисовать</label>
                <label><input type="radio" name="instrument" id="bigpaint-on-click" class="select-instrument" >Рисовать [x2]</label>
                <label><input type="radio" name="instrument" id="clear-on-move" class="select-instrument" >Стирать по наведению</label>
                <label><input type="radio" name="instrument" id="bigclear-on-move" class="select-instrument" >Стирать по наведению [x2]</label>
                <label><input type="radio" name="instrument" id="clear-on-click" class="select-instrument" >Стирать</label>
                <label><input type="radio" name="instrument" id="bigclear-on-click" class="select-instrument" >Стирать [x2]</label>
                <div class="btn-group">
                    <button onclick="paintAll()" class="btn btn-default">Залить всё</button>
                    <button onclick="clearPaintAll()" class="btn btn-default">Стереть всё</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="btn-group">
                <?= Html::button('<i class="fa fa-globe"></i> Сохранить тайлы', ['class' => 'btn btn-primary', 'onclick' => "saveAll()"]) ?>
                <?= Html::button('<i class="fa fa-refresh"></i> Сбросить', ['class' => 'btn btn-warning', 'onclick' => "reset()"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div id="map-info-label" class="label label-default"></div>
        <div id="map"></div>
    </div>
</div>
