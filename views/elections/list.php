<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\Election;

/* @var $this yii\base\View */
/* @var $elections app\models\Election[] */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Next elections')?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=Yii::t('app', 'Next elections')?></li>
    </ol>
</section>
<section class="content">
    <?php foreach ($elections as $election): ?>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title"><?=Yii::t('app', 'Elections of {0}', [Html::encode($election->post->name)])?></h3>
        </div>
        <div class="box-body">
            <?php switch ($election->status): 
                case Election::STATUS_NOT_STARTED: ?>
                not started
                <?php break;
                case Election::STATUS_REGISTRATION: ?>
                <p><?=Yii::t('app', 'Going registration for elections')?>. <?=MyHtmlHelper::timeFormatFuture($election->dateVotingStart)?>.</p>
                <p><?=Yii::t('app',($election->isOnlyParty)?'Only party leaders can make request':'Anyone can make request')?></p>
                
                <div class="btn-group">
                    <button class="btn btn-primary">Send request</button>
                </div>
                
                <?php break;
                case Election::STATUS_VOTING: ?>
                going voting <?=  MyHtmlHelper::timeFormatFuture($election->dateVotingEnd) ?>
                <?php break;
                case Election::STATUS_CALCULATING: ?>
                going calc res 
                <?php break;
                case Election::STATUS_ENDED: ?>
                ended
                <?php break ?>
            <?php endswitch ?>
        </div>
    </div>
    <?php endforeach ?>
    <pre>
        <?php var_dump($elections) ?>
    </pre>
</section>