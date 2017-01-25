<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\constitution\articles\postsonly\powers\Bills;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $post \app\models\politics\AgencyPost */
/* @var $powersBills Bills */

?>
<div class="box">
    <div class="box-header">
        <h4 class="box-title"><?= Yii::t('app', 'Active bills') ?></h4>
    </div>
    <div class="box-body">
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th><?= Yii::t('app', 'Bill creator') ?></th>
                    <th><?= Yii::t('app', 'Date Created') ?></th>
                    <th><?= Yii::t('app', 'Bill content') ?></th>
                    <th><?= Yii::t('app', 'Votes') ?></th>
                    <th><?= Yii::t('app', 'Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($post->state->getBillsActive()->orderBy(['dateCreated' => SORT_DESC])->all() as $bill): ?>
                    <tr>
                        <td>
                            <?php if ($bill->user || $bill->post): ?>
                                <?php if ($bill->user): ?>
                                    <?= LinkCreator::userLink($bill->user) ?><br>
                                <?php endif ?>
                                <?php if ($bill->post): ?>
                                    <?= Html::encode($bill->post->name) ?>
                                <?php endif ?>
                            <?php endif ?>
                        </td>
                        <td>
                            <span class="formatDate" data-unixtime="<?= $bill->dateCreated ?>" ><?= date('H:M d.m.Y', $bill->dateCreated) ?></span>
                        </td>
                        <td><?= $bill->render() ?></td>
                        <td class="text-center">
                            <span class="badge bg-green"><?= $bill->votesPlus ?></span>&nbsp;/&nbsp;<span class="badge bg-red"><?= $bill->votesMinus ?></span>&nbsp;/&nbsp;<span class="badge bg-gray"><?= $bill->votesAbstain ?></span>
                        </td>
                        <td>
                            <?= Html::a(Yii::t('app', 'Open bill info'), ['#!bills/view', 'id' => $bill->id], ['class' => 'btn btn-primary']) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <div class="btn-group">
            <?php if ($powersBills->isSelected(Bills::CREATE)): ?>
                <button data-post-id="<?= $post->id ?>" class="btn btn-primary new-bill-btn"><?= Yii::t('app', 'New bill') ?></button>
            <?php endif ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.new-bill-btn').click(function(){
        $('#work-new-bill-form-modal .modal-dialog').removeClass('modal-lg');
        createAjaxModal(
            'work/new-bill-form',
            {postId:$(this).data('postId')},
            '<?=Yii::t('app', 'New bill')?>',
            '<button id="new-bill-confirm-btn" onclick="$(\'#new-bill-form\').yiiActiveForm(\'submitForm\')" class="btn btn-primary new-bill-confirm-btn" ><?=Yii::t('app', 'Create new bill')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    });
</script>
