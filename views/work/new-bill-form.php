<?php

use yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $model app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $types array */

?>
<p>
    <strong><?=Yii::t('app', 'Bill type:')?></strong>
    <?=Html::dropDownList('Bill[protoId]', null, $types, ['id' => 'new-bill-proto'])?>
</p>