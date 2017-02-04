<?php

use yii\helpers\Html,
    app\components\widgets\BusinessViewAsWidget,
    app\components\widgets\BillVotesPieChartWidget,
    app\components\LinkCreator,
    app\models\MessageType,
    app\models\economics\CompanyDecisionVote;

/* @var $this yii\base\View */
/* @var $decision app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $user app\models\User */

$company = $decision->company;

?>
<section class="content-header">
    <div class="pull-right">
        <?=BusinessViewAsWidget::widget()?>
    </div>
    <h1>
        <?= LinkCreator::companyLink($company) ?>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box <?=$decision->isFinished ? ($decision->isApproved ? 'box-success' : 'box-danger') : ''?>">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=$decision->render()?>
                    </h2>
                </div>
                <div class="box-body">
                    <?=$decision->renderFull()?>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-sm-12">
            <div class="box">
                <div class="box-body">
                    <?php if ($decision->initiator): ?>
                    <p>
                        <strong><?=Yii::t('app', 'Initiator:')?></strong>
                        <?=LinkCreator::link($decision->initiator)?>
                    </p>
                    <?php endif ?>
                    <p>
                        <strong><?=Yii::t('app', 'Date Created')?>:</strong>
                        <span class="formatDate" data-unixtime="<?=$decision->dateCreated?>" ><?=date('H:M d.m.Y', $decision->dateCreated)?></span>
                    </p>
                    <p>
                        <strong><?=Yii::t('app', 'Date Voting Finished')?>:</strong>
                        <span class="formatDate" data-unixtime="<?=$decision->dateVotingFinished?>" ><?=date('H:M d.m.Y', $decision->dateVotingFinished)?></span>
                    </p>
                    <?php if ($decision->isFinished): ?>
                    <p>
                        <strong><?= $decision->isApproved ? Yii::t('app', 'Date approved') : Yii::t('app', 'Date declined')?></strong>
                        <span class="formatDate" data-unixtime="<?=$decision->dateFinished?>" ><?=date('H:M d.m.Y', $decision->dateFinished)?></span>
                    </p>
                    <?php endif ?>
                </div>
                <?php if (!$decision->isFinished): ?>
                <div class="box-footer">
                    <?php if ($decision->isAllreadyVoted($shareholder->getUtr())): ?>
                    <p class="help-block">
                        <?=Yii::t('app', 'You allready voted for this decision')?>
                    </p>
                    <?php else: ?>
                        <div class="help-block">
                            <?=Yii::t('app', 'You can vote as {0}', [LinkCreator::link($shareholder)])?>
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm vote-for-decision-btn" data-variant="<?=CompanyDecisionVote::VARIANT_PLUS?>" ><?=Yii::t('app', 'Vote FOR this decision')?></button>
                                <button class="btn btn-default btn-sm vote-for-decision-btn" data-variant="<?=CompanyDecisionVote::VARIANT_ABSTAIN?>" ><?=Yii::t('app', 'Vote abstain')?></button>
                                <button class="btn btn-danger btn-sm vote-for-decision-btn" data-variant="<?=CompanyDecisionVote::VARIANT_MINUS?>" ><?=Yii::t('app', 'Vote AGAINST this decision')?></button>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <?php endif ?>
            </div>
        </div>
        <div class="col-md-5 col-sm-12">
            <div class="box box-info">
                <div class="box-header">
                    <h4 class="box-title"><?=Yii::t('app', 'Votes')?>
                </div>
                <div class="box-body">
                    <?=BillVotesPieChartWidget::widget([
                        'data' => [
                            round($decision->votesPlus/$company->sharesIssued,2),
                            round($decision->votesAbstain/$company->sharesIssued,2),
                            round($decision->votesMinus/$company->sharesIssued,2),
                        ]
                    ])?>
                </div>
                <div class="box-footer">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary direct-chat">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Discussion about this decision') ?></h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="<?=Yii::t('app', 'Discussion members')?>">
                            <i class="fa fa-comments"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="decision-messages-list" class="direct-chat-messages" data-last-update-time="<?=time()?>" >
                        <?php if (count($decision->messages)): ?>
                            <?php foreach ($decision->messages as $message): ?>
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
                            <?php foreach ($company->shares as $share): ?>
                            <li>
                                <a href="javascript:void(0);">
                                    <?=Html::img("//placehold.it/50x50", ['class' => 'contacts-list-img'])?>
                                    <div class="contacts-list-info">
                                        <span class="contacts-list-name">
                                            <?//=Yii::t('yii', '(not set)')?>
                                            <!--<small class="contacts-list-date pull-right"></small>-->
                                        </span>
                                        <span class="contacts-list-msg"><?=Html::encode($share->master->name)?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
                <div class="box-footer">
                <?php if (!$decision->isFinished): ?>
                    <form action="#" onsubmit="sendMessage(); return false;" method="post">
                        <div class="input-group">
                            <input id="decision-discussion-message" type="text" name="message" placeholder="<?=Yii::t('app', 'Type Message ...')?>" class="form-control" autocomplete="off" >
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-flat"><?=Yii::t('app', 'Send')?></button>
                            </span>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="help-block text-center"><?=Yii::t('app', 'You can`t discuss this decision')?></p>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    
    $('#select-using-utr').change(function(){
        current_page_params.utr = $('#select-using-utr').val();
        reload_page();
    });
    
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
        var text = $('#decision-discussion-message').val();
        if (text) {
            $('#decision-discussion-message').val('');
            $('#decision-messages-list').append('<div id="direct-chat-msg-loader" class="direct-chat-msg right"><div class="direct-chat-text">…</div></div>')
            json_request('messages/send', {
                typeId: <?=MessageType::DECISION_DISQUSSION?>,
                recipientId: <?=$decision->id?>,
                text: text
            }, true, false, function(data) {
                var html = generateMessageBlock(data.result);
                if ($('#decision-messages-list').children('.help-block').length) {
                    $('#decision-messages-list').empty();
                }
                $('#direct-chat-msg-loader').remove();
                $('#decision-messages-list').append(html).scrollTop(99999999);
                prettyDates();
            }, 'POST');
        }
    }
    
    function autoUpdate()
    {
        var lastUpdateTime = parseInt($('#decision-messages-list').data('lastUpdateTime'));
        get_json('messages/get', {
            typeId: <?=MessageType::DECISION_DISQUSSION?>,
            recipientId: <?=$decision->id?>,
            lastUpdateTime: lastUpdateTime
        }, function(data) {
            if (data.result) {
                var html = '';
                for (var i = 0; i < data.result.length; i++) {
                    html += generateMessageBlock(data.result[i]);
                }
                if (html && $('#decision-messages-list').children('.help-block').length) {
                    $('#decision-messages-list').empty();
                }
                $('#decision-messages-list').append(html);
                $('#decision-messages-list').data('lastUpdateTime', Math.round((new Date()).getTime()/1000));
                prettyDates();
            }
        }, true);
    }
    
    
    $(function(){
        $('#decision-messages-list').scrollTop(99999999);
        <?php if (!$decision->isFinished): ?>
        currentPageInterval = setInterval(autoUpdate, 5000);
        <?php endif ?>
        
        $('.vote-for-decision-btn').click(function() {
            json_request('company/decision-vote', {
                id: <?=$decision->id?>,
                utr: <?=$shareholder->getUtr()?>,
                variant: $(this).data('variant')
            }, false, false, '', 'POST');
        });
    });
</script>