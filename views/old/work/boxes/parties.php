<?php

use app\components\LinkCreator,
    app\models\politics\constitution\articles\postsonly\powers\Parties;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $post \app\models\politics\AgencyPost */
/* @var $powersParties Parties */

?>

<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?= Yii::t('app', 'Parties') ?></h4>
    </div>
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            <?php if ($powersParties->isSelected(Parties::ACCEPT)): ?>
                <li class="active">
                    <a href="#approve-parties-registration" data-toggle="tab" aria-expanded="true"><?=Yii::t('app', 'Approve parties registration')?></a>
                </li>
            <?php endif ?>
            <?php if ($powersParties->isSelected(Parties::REVOKE)): ?>
                <li <?=$powersParties->isSelected(Parties::ACCEPT)?'':'class="active"'?> >
                    <a href="#revoke-parties-registration" data-toggle="tab" aria-expanded="true"><?=Yii::t('app', 'Revoke parties registration')?></a>
                </li>
            <?php endif ?>
            </ul>
            <div class="tab-content">
            <?php if ($powersParties->isSelected(Parties::ACCEPT)): ?>
                <div class="tab-pane active" id="approve-parties-registration">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Party') ?></th>
                                <th><?= Yii::t('app', 'Creator') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($post->state->partiesUnconfirmed as $party): ?>
                                <tr>
                                    <td><?= LinkCreator::partyLink($party) ?></td>
                                    <td>
                                        <?= $party->leaderPost && $party->leaderPost->user ? LinkCreator::userLink($party->leaderPost->user) : Yii::t('app', '(not set)') ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-confirm-party-registration" data-party-id="<?=$party->id?>" ><i class="fa fa-check"></i> <?= Yii::t('app', 'Approve registration') ?></button>
                                            <button class="btn btn-danger btn-revoke-party-registration" data-party-id="<?=$party->id?>" ><i class="fa fa-ban"></i> <?= Yii::t('app', 'Revoke registration') ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
            <?php if ($powersParties->isSelected(Parties::REVOKE)): ?>
                <div class="tab-pane <?=$powersParties->isSelected(Parties::ACCEPT)?'':'active'?>" id="revoke-parties-registration">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Party') ?></th>
                                <th><?= Yii::t('app', 'Leader') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($post->state->parties as $party): ?>
                                <tr>
                                    <td><?= LinkCreator::partyLink($party) ?></td>
                                    <td>
                                        <?= $party->leaderPost && $party->leaderPost->user ? LinkCreator::userLink($party->leaderPost->user) : Yii::t('app', '(not set)') ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-danger btn-block btn-revoke-party-registration" data-party-id="<?=$party->id?>" ><i class="fa fa-ban"></i> <?= Yii::t('app', 'Revoke registration') ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
            </div>
        </div>
    </div>
<!--    <div class="box-footer">
        <div class="btn-group">

        </div>
    </div>-->
</div>
<script type="text/javascript">
    
    $('.btn-confirm-party-registration').click(function(){
        var partyId = $(this).data('partyId');
        json_request(
            'party/confirm', 
            {postId:<?=$post->id?>, partyId: partyId},
            false, false, null, 'POST'
        );
    });
    
    $('.btn-revoke-party-registration').click(function(){
        var partyId = $(this).data('partyId');
        json_request(
            'party/revoke', 
            {postId:<?=$post->id?>, partyId: partyId},
            false, false, null, 'POST'
        );
    });
    
</script>
