<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\components\widgets\BillVotesPieChartWidget,
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
    <div class="row">
        <div class="col-md-7 col-sm-12">
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
                    <?php
                        $canVote = false;
                        $canDiscuss = false;
                    ?>
                    <?php foreach ($user->getPostsByState($bill->stateId)->all() as $post): ?>
                        <?php
                        $canVoteCurrent = false;
                        /* @var $powersBills Bills */
                        $powersBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS);
                        if ($powersBills->isSelected(Bills::VOTE)) {
                            $canVote = true;
                            $canVoteCurrent = true;
                        }
                        if ($powersBills->isSelected(Bills::DISCUSS)) {
                            $canDiscuss = true;
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
        <div class="col-md-5 col-sm-12">
            <div class="box box-info">
                <div class="box-header">
                    <h4 class="box-title"><?=Yii::t('app', 'Votes')?>
                </div>
                <div class="box-body">
                    <?=BillVotesPieChartWidget::widget(['data' => [$bill->votesPlus, $bill->votesAbstain, $bill->votesMinus]])?>
                </div>
                <div class="box-footer">
                    <?=MyHtmlHelper::a('<i class="fa fa-info"></i> '.Yii::t('app', 'Additional information'), 'createAjaxModal("bills/view-modal", {id:'.$bill->id.'})', ['class' => 'btn btn-default btn-block'])?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary direct-chat direct-chat-primary">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Discussion about this bill') ?></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="<?=Yii::t('app', 'Discussion members')?>">
                            <i class="fa fa-comments"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="direct-chat-messages" style="height:400px">
                        <?php if (count($bill->messages)): ?>
                            <?php foreach ($bill->messages as $message): ?>
                            <div class="direct-chat-msg <?=$message->senderId == $user->id?'right':''?>">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left"><?=Html::encode($message->sender->name)?></span>
                                    <span class="direct-chat-timestamp pull-right formatDate" data-unixtime="<?=$message->dateCreated?>" ><?=date('H:i:s d.m.Y', $message->dateCreated)?></span>
                                </div>
                                <?=Html::img($message->sender->avatar, ['class' => 'direct-chat-img'])?>
                                <div class="direct-chat-text">
                                    <?=$message->textHtml?>
                                </div>
                            </div>
                            <?php endforeach ?>
                        <?php else: ?>
                        <p class="help-block text-center" ><?=Yii::t('app', 'Not any messages yet')?></p>
                        <?php endif ?>
                    </div>
                    <div class="direct-chat-contacts half-width" style="height:400px">
                        <ul class="contacts-list">
                            <?php foreach ($bill->votersPosts as $voter): ?>
                            <li>
                                <a href="#!profile&id=<?=$voter->user->id?>">
                                    <?=Html::img($voter->user->avatar, ['class' => 'contacts-list-img'])?>
                                    <div class="contacts-list-info">
                                        <span class="contacts-list-name">
                                            <?=Html::encode($voter->user->name)?>
                                            <!--<small class="contacts-list-date pull-right"></small>-->
                                        </span>
                                        <span class="contacts-list-msg"><?=Html::encode($voter->name)?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
                <div class="box-footer">
                <?php if ($canDiscuss): ?>
                    <form action="#" onsubmit="return false;" method="post">
                        <div class="input-group">
                            <input type="text" name="message" placeholder="<?=Yii::t('app', 'Type Message ...')?>" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-flat"><?=Yii::t('app', 'Send')?></button>
                            </span>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="help-block text-center"><?=Yii::t('app', 'You can`t discuss this bill')?></p>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>