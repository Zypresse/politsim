<?php

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */

?>
<div class="row">
    <div class="col-md-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'not started')?></p>
        <p><strong><?=Yii::t('app', 'Registration start:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateRegistrationStart?>"><?=date('d-m-y', $election->dateRegistrationStart)?></span></p>
    </div>
</div>