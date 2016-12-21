<?php

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */
/* @var $showRegisterButton boolean */

?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'candidats registration')?></p>
        <p><strong><?=Yii::t('app', 'Registration finish:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateRegistrationEnd?>"><?=date('d-m-y', $election->dateRegistrationEnd)?></span></p>
        <?php if ($showRegisterButton): ?>
        <div class="btn-group">
            <button class="btn btn-primary send-election-request-modal-btn" data-id="<?=$election->id?>" ><?=Yii::t('app', 'Register to this elections')?></button>
        </div>
        <?php endif ?>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-solid box-info">
            <div class="box-header">
                <h4 class="box-title"><?=Yii::t('app', 'Candidats')?></h4>
            </div>
            <div class="box-body">
                <p><?=Yii::t('app', 'No one candidat registered')?></p>
            </div>
        </div>
    </div>
</div>