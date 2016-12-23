<?php

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */

?>
<div class="row">
    <div class="col-md-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'results calculating')?></p>
        <p><strong><?=Yii::t('app', 'Voting finish:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateVotingEnd?>"><?=date('d-m-y', $election->dateVotingEnd)?></span></p>
    </div>
</div>