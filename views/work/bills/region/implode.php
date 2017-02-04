<?php

use yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[region1Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Main region'))?>
<?=$form->field($model, 'dataArray[region2Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Children region'))?>
<div id="same-regions-alert" class="alert alert-warning">
    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
    <h4><i class="icon fa fa-warning"></i> <?=Yii::t('app', 'Alert!')?></h4>
    <?=Yii::t('app', 'Please select two different regions')?>
</div>
<div id="map"></div>
