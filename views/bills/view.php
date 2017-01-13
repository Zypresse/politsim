<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\Bills;

/* @var $this yii\base\View */
/* @var $bill app\models\politics\bills\Bill */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Bill #{0}', [$bill->id])?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($bill->state)?></li>
        <li><?=Yii::t('app', 'Bills')?></li>
        <li class="active"><?=Yii::t('app', 'Bill #{0}', [$bill->id])?></li>
    </ol>
</section>
<section class="content">
    <div class="box-group">
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">
                    <?=$bill->render()?>
                </h2>
            </div>
            <div class="box-body">
                <p>
                    <strong><?=Yii::t('app', 'Bill creator')?>:</strong>
                    <?php if ($bill->user || $bill->post): ?>
                        <?php if ($bill->user): ?>
                            <?=LinkCreator::userLink($bill->user)?>
                        <?php endif ?>
                        <?php if ($bill->post): ?>
                            (<?=Html::encode($bill->post->name)?>)
                        <?php endif ?>
                    <?php endif ?>
                </p>
                <p>
                    <strong><?=Yii::t('app', 'Date Created')?>:</strong>
                    <span class="formatDate" data-unixtime="<?=$bill->dateCreated?>" ><?=date('H:M d.m.Y', $bill->dateCreated)?></span>
                </p>
                
            </div>
            <div class="box-footer">
                <?php $canVote = false; ?>
                <?php foreach ($user->getPostsByState($bill->stateId)->all() as $post): ?>
                <?php 
                    $canVoteCurrent = false;
                    /* @var $powersBills Bills */
                    $powersBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS); 
                    if ($powersBills->isSelected(Bills::VOTE)) {
                        $canVote = true;
                        $canVoteCurrent = true;
                    }
                ?>
                <?php if ($canVoteCurrent): ?>
                <div class="help-block">
                    <?=Yii::t('app', 'You can vote as {0}', [Html::encode($post->name)])?>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm"><?=Yii::t('app', 'Vote FOR this bill')?></button>
                        <button class="btn btn-default btn-sm"><?=Yii::t('app', 'Vote abstain')?></button>
                        <button class="btn btn-danger btn-sm"><?=Yii::t('app', 'Vote AGAINST this bill')?></button>
                    </div>
                </div>
                <?php endif ?>
                <?php endforeach ?>
                <?php if (!$canVote): ?>
                <p class="help-block"><?=Yii::t('app', 'You can`t vote for this bill')?></p>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>