<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $state app\models\politics\State */
/* @var $user app\models\User */

$isHaveCitizenship = $user->isHaveCitizenship($state->id);

?>
<section class="content-header">
    <h1>
        <?=Html::encode($state->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=Html::img($state->flag, ['style' => 'height: 8px; vertical-align: baseline;'])?> <?=Html::encode($state->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <?=Html::img($state->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'National flag')?></em>
                </div>
            </div>
            <?php if ($state->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $state->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'National anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
            <div class="box box-info box-solid">
                <div class="box-header">
                    <h4 class="box-title"><?=Yii::t('app', 'Additional info')?></h4>
                </div>
                <div class="box-body">
                    <a href="#!state/constitution&id=<?=$state->id?>" class="btn btn-default btn-block"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Constitution')?></a>
                    <?=Html::a('<i class="fa fa-group"></i> '.Yii::t('app', 'Population'),'#!population/state&id='.$state->id,['class' => 'btn btn-default btn-block'])?>
                    <a href="#!elections/state&id=<?=$state->id?>" class="btn btn-default btn-block"><i class="fa fa-university"></i> <?=Yii::t('app', 'Elections')?></a>
                    <a href="#!map/state&id=<?=$state->id?>" class="btn btn-default btn-block"><i class="fa fa-globe"></i> <?=Yii::t('app', 'Political map')?></a>
                    <a href="#!state/bills&id=<?=$state->id?>" class="btn btn-default btn-block"><i class="fa fa-list"></i> <?=Yii::t('app', 'Bills')?></a>
                </div>
            </div>
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Available actions')?></h3>
                </div>
                <div class="box-body">
                    <?php if ($isHaveCitizenship):?>
                    <p><?=Yii::t('app','You have this state citizenship')?></p>
                    <?php endif ?>
                    <?php if ($isHaveCitizenship):?>
                    <button onclick="if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) json_request('citizenship/cancel', {stateId: <?=$state->id?>})" class="btn btn-block btn-danger"><i class="fa fa-sign-out"></i> <?=Yii::t('app', 'Fire citizenship')?></button>
                    <?php else: ?>
                    <button onclick="json_request('citizenship/request', {stateId: <?=$state->id?>})" class="btn btn-block btn-primary"><i class="fa fa-sign-in"></i> <?=Yii::t('app', 'Make request for citizenship')?></button>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">                    
                    <h1>
                        <?=Html::encode($state->name)?>
                        <small>(<?=Html::encode($state->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'State structure')?></strong></td>
                                        <td><?=$state->stateStructure->name?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'Goverment form')?></strong></td>
                                        <td><?=$state->govermentForm->name?></td>
                                    </tr>
                                    <?php if ($state->leaderPost): ?>
                                    <tr>
                                        <td><strong><i class="fa fa-user"></i> <?=Html::encode($state->leaderPost->name)?></strong></td>
                                        <td><?=$state->leaderPost->user ? LinkCreator::userLink($state->leaderPost->user) : ' — '?></td>
                                    </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-building"></i> <?=Yii::t('app', 'Capital city')?></strong></td>
                                        <td>
                                            <?php if ($state->city): ?>
                                                <?=LinkCreator::cityLink($state->city)?>
                                            <?php else: ?>
                                                <?=Yii::t('app', 'Not set')?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-group"></i> <?=Yii::t('app', 'Population')?></strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($state->population, 'h')?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Goverment posts')?></h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered no-margin">
                        <thead>
                            <tr>
<!--                                <th><?=Yii::t('app', 'Post name')?></th>
                                <th><?=Yii::t('app', 'User')?></th>-->
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($state->posts as $post): ?>
                            <tr>
                                <td><?=Html::encode($post->name)?></td>
                                <td>
                                    <?php if ($post->user): ?>
                                    <?=LinkCreator::userLink($post->user)?>
                                    <?php else: ?> — <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Goverment agencies')?></h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered no-margin">
                        <thead>
                            
                        </thead>
                        <tbody>
                        <?php foreach ($state->agencies as $agency): ?>
                            <tr>
                                <td><?=LinkCreator::agencyLink($agency)?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Regions')?></h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered no-margin">
                        <thead>
                            <tr>
                                <th><?=Yii::t('app', 'Name')?></th>
                                <th><i class="fa fa-group"></i> <?=Yii::t('app', 'Population')?></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($state->regions as $region): ?>
                            <tr>
                                <td><?=LinkCreator::regionLink($region)?></td>
                                <td><?=MyHtmlHelper::formateNumberword($region->population, 'h')?> <?=Html::a(Yii::t('app', 'Population'),'#!population/region&id='.$region->id,['class' => 'btn btn-info btn-xs'])?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
