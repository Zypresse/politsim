<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $bill app\models\politics\bills\Bill */
/* @var $agency app\models\politics\Agency */
/* @var $otherPost app\models\politics\AgencyPost */

?>
<p>
    <?=Yii::t('app/bills', 'Create new agency post «{0}» ({1}) in agency {2}', [
        Html::encode($bill->dataArray['name']),
        Html::encode($bill->dataArray['nameShort']),
        LinkCreator::agencyLink($agency),
    ])?>
</p>
<p>
    <strong><?=Yii::t('app', 'Destignation type')?>:</strong> <?=Yii::t('app', 'By other agency post')?><br>
    <strong><?=Yii::t('app', 'Agency post')?>:</strong> <?=Html::encode($otherPost->name)?><br>
</p>