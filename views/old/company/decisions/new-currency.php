<?php

use app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Currency name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Currency short name'))?>
<?=$form->field($model, 'dataArray[exchangeRate]')->textInput(['type' => 'number'])->label(Yii::t('app', 'Exchange rate'))?>
<br>
<div class="callout callout-info">
    <h4><i class="fa fa-balance-scale"></i> <?=Yii::t('app', 'Exchange rate')?></h4>
    <p><?=Yii::t('app', 'Start exchange rate will be set to <span id="rate-count-currency">1</span> <span id="new-currency-nameshort">new currency</span> for <span id="rate-count-international">1</span> {0}', [
        MyHtmlHelper::icon('money', ''),
    ])?></p>
</div>
