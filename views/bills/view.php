<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\components\widgets\BillVotesPieChartWidget,
    app\models\MessageType,
    app\models\politics\bills\BillVote,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Bills;

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
                    <p>
                        <strong><?=Yii::t('app', 'Date Voting Finished')?>:</strong>
                        <span class="formatDate" data-unixtime="<?=$bill->dateVotingFinished?>" ><?=date('H:M d.m.Y', $bill->dateVotingFinished)?></span>
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

                    $allreadyVoted = $bill->isAllreadyVoted($post->id);

                    ?>
                    <?php if ($allreadyVoted): ?>
                    <p class="help-block">
                        <?=Yii::t('app', 'You allready voted for this bill')?>
                    </p>
                    <?php elseif ($canVoteCurrent): ?>
                        <div class="help-block">
                            <?=Yii::t('app', 'You can vote as {0}', [Html::encode($post->name)])?>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm vote-for-bill-btn" data-variant="<?=BillVote::VARIANT_PLUS?>" data-post-id="<?=$post->id?>" ><?=Yii::t('app', 'Vote FOR this bill')?></button>
                                <button class="btn btn-default btn-sm vote-for-bill-btn" data-variant="<?=BillVote::VARIANT_ABSTAIN?>" data-post-id="<?=$post->id?>" ><?=Yii::t('app', 'Vote abstain')?></button>
                                <button class="btn btn-danger btn-sm vote-for-bill-btn" data-variant="<?=BillVote::VARIANT_MINUS?>" data-post-id="<?=$post->id?>" ><?=Yii::t('app', 'Vote AGAINST this bill')?></button>
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
            <div class="box box-solid box-primary direct-chat">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Discussion about this bill') ?></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="<?=Yii::t('app', 'Discussion members')?>">
                            <i class="fa fa-comments"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="bill-messages-list" class="direct-chat-messages" data-last-update-time="<?=time()?>" >
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
                    <div class="direct-chat-contacts half-width">
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
                    <form action="#" onsubmit="sendMessage(); return false;" method="post">
                        <div class="input-group">
                            <input id="bill-discussion-message" type="text" name="message" placeholder="<?=Yii::t('app', 'Type Message ...')?>" class="form-control">
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
<script type="text/javascript">
    
    function generateMessageBlock(data)
    {
        return '<div class="direct-chat-msg '+(data.senderId == <?=$user->id?>?'right':'')+'">'+
                                '<div class="direct-chat-info clearfix">'+
                                    '<span class="direct-chat-name pull-left">'+data.sender.name+'</span>'+
                                    '<span class="direct-chat-timestamp pull-right formatDate" data-unixtime="'+data.dateCreated+'" >'+(new Date(data.dateCreated)).toISOString()+'</span>'+
                                '</div>'+
                                '<img src="'+data.sender.avatar+'" alt="" class="direct-chat-img">'+
                                '<div class="direct-chat-text">'+
                                    data.textHtml+
                                '</div>'+
                            '</div>';
    }
    
    function sendMessage()
    {
        var text = $('#bill-discussion-message').val();
        if (text) {
            json_request('messages/send', {
                typeId: <?=MessageType::BILL_DISQUSSION?>,
                recipientId: <?=$bill->id?>,
                text: text
            }, true, false, function(data) {
                var html = generateMessageBlock(data.result);
                if ($('#bill-messages-list').children('.help-block')) {
                    $('#bill-messages-list').empty();
                }
                $('#bill-messages-list').append(html).scrollTop($('#bill-messages-list').height()+100);
                prettyDates();
            }, 'POST');
        }
    }
    
    function autoUpdate()
    {
        var lastUpdateTime = parseInt($('#bill-messages-list').data('lastUpdateTime'));
        get_json('messages/get', {
            typeId: <?=MessageType::BILL_DISQUSSION?>,
            recipientId: <?=$bill->id?>,
            lastUpdateTime: lastUpdateTime
        }, function(data) {
            if (data.result) {
                var html = '';
                for (var i = 0; i < data.result.length; i++) {
                    html += generateMessageBlock(data.result);
                }
                if (html && $('#bill-messages-list').children('.help-block')) {
                    $('#bill-messages-list').empty();
                }
                $('#bill-messages-list').append(html);
                prettyDates();
            }
        }, true);
    }
    
    
    $(function(){
        $('#bill-messages-list').scrollTop($('#bill-messages-list').height()+100);
        currentPageInterval = setInterval(autoUpdate, 5000);
        
        $('.vote-for-bill-btn').click(function() {
            json_request('bills/vote', {
                billId: <?=$bill->id?>,
                postId: $(this).data('postId'),
                variant: $(this).data('variant')
            }, false, false, '', 'POST');
        });
    });
    
</script>