<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\map\Region;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\map\City */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile("/js/admin/map.js");
$this->registerJs("initMap('city', {$model->id})");
?>

<div class="city-form row">
    <div class="col-lg-3">
        <?php $form = ActiveForm::begin(); ?>

        <?=
        $form->field($model, 'regionId')->widget(Select2::class, [
            'data' => ArrayHelper::map(Region::findAll(), 'id', 'name'),
        ])
        ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nameShort')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'flag')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'anthem')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'population')->textInput() ?>
        

        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-save"></i> Сохранить данные', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        
        
        <div class="form-group" id="instruments-list" >
            <label for="instrument">Малярня</label>
            <div>
                <label style="display: none"><input type="radio" name="instrument" id="paint-on-move" class="select-instrument" >Красить по наведению</label>
                <label><input type="radio" name="instrument" id="paint-on-click" class="select-instrument" >Рисовать</label>
                <label style="display: none"><input type="radio" name="instrument" id="clear-on-move" class="select-instrument" >Стирать по наведению</label>
                <label><input type="radio" name="instrument" id="clear-on-click" class="select-instrument" >Стирать</label>
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
