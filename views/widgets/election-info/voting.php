<?php

use app\components\LinkCreator;

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */

$canVote = $election->canVote($viewer);

?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'going voting')?></p>
        <p><strong><?=Yii::t('app', 'Voting finish:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateVotingEnd?>"><?=date('d-m-y', $election->dateVotingEnd)?></span></p>
        <?php if ($canVote): ?>
        <div class="btn-group">
            <button class="btn btn-primary election-vote-modal-btn" onclick="electionVoteModal(<?=$election->id?>)" ><?=Yii::t('app', 'Vote')?></button>
        </div>
        <?php endif ?>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-solid box-info">
            <div class="box-header">
                <h4 class="box-title"><?=Yii::t('app', 'Exitpolls')?></h4>
            </div>
            <div class="box-body">
                <p>Тут будут экситполлы</p>
            </div>
        </div>
    </div>
</div>