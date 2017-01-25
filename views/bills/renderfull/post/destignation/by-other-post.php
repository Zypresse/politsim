<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $bill app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $otherPost app\models\politics\AgencyPost */

?>
<p>
    <?=Yii::t('app/bills', 'Change agency post «{0}» destignation type to destignation by agency post «{1}»', [
        Html::encode($post->name),
        Html::encode($otherPost->name),
    ])?>
</p>
