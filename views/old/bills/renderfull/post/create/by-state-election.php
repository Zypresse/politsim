<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $bill app\models\politics\bills\Bill */
/* @var $agency app\models\politics\Agency */
/* @var $settings string[] */

?>
<p>
    <?=Yii::t('app/bills', 'Create new agency post «{0}» ({1}) in agency {2}', [
        Html::encode($bill->dataArray['name']),
        Html::encode($bill->dataArray['nameShort']),
        LinkCreator::agencyLink($agency),
    ])?>
</p>
<p>
    <strong><?=Yii::t('app', 'Destignation type')?>:</strong> <?=Yii::t('app', 'By state election')?><br>
    <?=Yii::t('app', 'Elections settings: {0}', [
        implode(', ', $settings),
    ])?>
</p>
<p>
    <strong><?=Yii::t('app', 'Terms of office')?>:</strong>
    <?=MyHtmlHelper::formateNumberword($bill->dataArray['toValue'], 'd')?>
</p>
<p>
    <strong><?=Yii::t('app', 'Terms of elections')?>:</strong><br>
    <?=Yii::t('app', 'Registration for elections:')?> <?=MyHtmlHelper::formateNumberword($bill->dataArray['teValue'], 'd')?><br>
    <?=Yii::t('app', 'Pause between registration and voting:')?> <?=MyHtmlHelper::formateNumberword($bill->dataArray['teValue2'], 'd')?><br>
    <?=Yii::t('app', 'Voting:')?> <?=MyHtmlHelper::formateNumberword($bill->dataArray['teValue3'], 'd')?>
</p>
