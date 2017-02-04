<?php

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[anthem]')->textInput()->label(Yii::t('app', 'State anthem'))?>
<div class="callout callout-info">
    <h4><i class="fa fa-exclamation-circle"></i> <?=Yii::t('app', 'Use only SoundCloud links!')?></h4>
    <p><?=Yii::t('app', 'Please upload your music to <a href="https://soundcloud.com" target="_blank" >SoundCloud.com</a>, press "share" link and copypaste link to this field')?></p>
</div>
