<?php

use app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $parties \app\models\politics\Party[] */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Parties')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::userLink($user)?></li>
        <li class="active"><i class="fa fa-group"></i> <?=Yii::t('app', 'Parties')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <?php if (count($approved)): ?>
                <div class="box">
                    <div class="box-header">
                        <h4><?=Yii::t('app', 'Memberships')?></h4>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Party')?></th>
                                    <th><?=Yii::t('app', 'Date approved')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($approved as $membership): ?>
                                <tr>
                                    <td>
                                        <?=LinkCreator::partyLink($membership->party)?>
                                    </td>
                                    <td>
                                        <?=MyHtmlHelper::timeAutoFormat($membership->dateApproved)?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>                   
                    </div>
                </div>
            <?php else: ?>
                <div class="box">
                    <div class="box-header">
                        <h4><?=Yii::t('app', 'You have no one membership')?></h4>
                    </div>
                    <div class="box-body">
                        <?=Yii::t('app', 'You can join to existing party or create a new one')?>
                    </div>
                </div>
                <?php if (count($user->states)): ?>
                    <?php foreach ($user->states as $state): ?>
                    <div class="box">
                        <div class="box-title">
                            <h4><?=LinkCreator::stateLink($state)?></h4>
                        </div>
                        <div class="box-body">
                            <div class="btn-group">
                                <button class="create-party-btn btn btn-primary" data-state-id="<?=$state->id?>" ><i class="fa fa-plus"></i> <?=Yii::t('app', 'Create party')?></button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                <?php else: ?>
                <div class="box">
                    <div class="box-body">
                        <?=Yii::t('app', 'You have not citizehship and you can not create parties')?>
                    </div>
                </div>
                <?php endif ?>
            <?php endif ?>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h4><?=Yii::t('app', 'Membership requests')?></h4>
                </div>
                <div class="box-body">
                    <?php if (count($requested)): ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Party')?></th>
                                    <th><?=Yii::t('app', 'Request date')?></th>
                                    <th><?=Yii::t('app', 'Action')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($requested as $membership): ?>
                                <tr>
                                    <td>
                                        <?=LinkCreator::partyLink($membership->party)?>
                                    </td>
                                    <td>
                                        <?=MyHtmlHelper::timeAutoFormat($membership->dateCreated)?>
                                    </td>
                                    <td>
                                        <button onclick="json_request('membership/cancel', {stateId: <?=$membership->partyId?>})" class="btn btn-danger btn-xs"><?=Yii::t('app', 'Cancel request')?></button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?=Yii::t('app', 'You have no one membership request')?></p>
                    <?php endif ?>
                </div>
            </div>            
        </div>
    </div>
</section>

<script type="text/javascript">
    
    $('.create-party-btn').click(function(){
        createAjaxModal(
                'party/create-form',
                {stateId: $(this).data('stateId')},
                '<?=Yii::t('app', 'Party creation')?>',
                '<button class="btn btn-primary" onclick="$(\'#create-party-form\').yiiActiveForm(\'submitForm\')"><?=Yii::t('app', 'Create')?></button> <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>');
    });
    
</script>