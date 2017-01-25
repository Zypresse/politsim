<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $bill app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $city app\models\politics\City */
/* @var $settings string[] */

?>
<p>
    <?=Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by elections in city {1} with next settings: {2}', [
        Html::encode($post->name),
        LinkCreator::cityLink($city),
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
