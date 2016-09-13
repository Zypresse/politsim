<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $party app\models\Party */
/* @var $user app\models\User */

$isHaveMembership = $user->isHaveMembership($party->id);

?>
<section class="content-header">
    <h1>
        <?=Html::encode($party->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=$party->flag ? Html::img($party->flag, ['style' => 'height: 10px; vertical-align: baseline;']) : ''?> <?=Html::encode($party->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <?php if ($party->flag || $party->anthem): ?>
        <div class="col-md-4">
            <?php if ($party->flag): ?>
            <div class="box">
                <div class="box-body">
                    <?=Html::img($party->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'Party flag')?></em>
                </div>
            </div>
            <?php endif ?>
            <?php if ($party->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $party->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'Party anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($party->flag || $party->anthem)?8:12?>">
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($party->name)?>
                         <small>(<?=Html::encode($party->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <?php if ($party->dateDeleted): ?>
                    <div class="callout callout-danger">
                        <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Party deleted!')?></h4>

                        <p><?=Yii::t('app', 'This party has been deleted')?> <?=MyHtmlHelper::timeAutoFormat($party->dateDeleted)?></p>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Available actions')?></h3>
                </div>
                <div class="box-body">
                    <p>
                        <?php if ($isHaveMembership):?>
                            <?=Yii::t('app','You have this party membership')?>
                        <?php endif ?>
                    </p>
                    <div class="btn-group">
                        <?php if ($isHaveMembership):?>
                            <button onclick="if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) json_request('membership/cancel', {partyId: <?=$party->id?>})" class="btn btn-danger"><?=Yii::t('app', 'Fire membership')?></button>
                        <?php else: ?>
                            <?php if (!$party->dateDeleted): ?>
                                <button onclick="json_request('membership/request', {partyId: <?=$party->id?>})" class="btn btn-primary"><?=Yii::t('app', 'Make request for membership')?></button>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
