<?php

use app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Company name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Company short name'))?>
<?=$form->field($model, 'dataArray[flag]')->textInput()->label(Yii::t('app', 'Company flag'))?>
<?=$form->field($model, 'dataArray[sharesPrice]')->textInput(['type' => 'number'])->label(Yii::t('app', 'Shares Price').' '.MyHtmlHelper::icon('money', 'vertical-align: bottom;'))?>
<?=$form->field($model, 'dataArray[sharesIssued]')->textInput(['type' => 'number'])->label(Yii::t('app', 'Shares Issued'))?>

<div class="help-block">
    <?=Yii::t('app', 'This company gets <span id="company-registration-cost">0</span> {0} from budget', [
        MyHtmlHelper::icon('money', 'vertical-align: bottom;'),
    ])?>
</div>
