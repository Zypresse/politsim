<?php

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[flag]')->textInput()->label(Yii::t('app', 'State flag'))?>
<div class="callout callout-info">
    <h4><i class="fa fa-exclamation-circle"></i> <?=Yii::t('app', 'Use direct link to image (ends with .jpg or .png)!')?></h4>
    <p><?=Yii::t('app', 'Please use safe and stable image hostings like <a href="https://imgur.com" target="_blank" >Imgur.com</a>')?></p>
</div>
