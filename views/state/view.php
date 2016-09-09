<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $state app\models\State */
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
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    <h1>
                        <?=Html::encode($state->name)?>
                         <small>(<?=Html::encode($state->nameShort)?>)</small>
                    </h1>
                    <div class="row">
                        <div class="col-md-6">
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
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-building"></i> <?=Yii::t('app', 'Capital city')?></strong></td>
                                        <td>
                                            <?php if ($state->city): ?>
                                                <a href="#!city&id=<?=$state->cityId?>"><?=Html::encode($state->city->name)?></a>
                                            <?php else: ?>
                                                <?=Yii::t('app', 'Not set')?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-group"></i> <?=Yii::t('app', 'Population')?></strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($state->population)?> <?=Html::a(Yii::t('app', 'Population info'),'#!population/state&id='.$state->id,['class' => 'btn btn-info btn-xs'])?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Regions')?></h3>
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
                                <td><?=$region->flag ? Html::img($region->flag, ['style' => 'height: 8px']) : ''?> <?=Html::a(Html::encode($region->name), '#!region&id='.$region->id)?></td>
                                <td><?=MyHtmlHelper::formateNumberword($region->population)?> <?=Html::a(Yii::t('app', 'Population info'),'#!population/region&id='.$region->id,['class' => 'btn btn-info btn-xs'])?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div><div class="box">
                <div class="box-header">
                    <h3><?=Yii::t('app', 'Available actions')?></h3>
                </div>
                <div class="box-body">
                    <p>
                        <?php if ($isHaveCitizenship):?>
                            <?=Yii::t('app','You have this state citizenship')?>
                        <?php endif ?>
                    </p>
                    <div class="btn-group">
                        <?php if ($isHaveCitizenship):?>
                            <button onclick="if (confirm('<?=Yii::t('app', 'Are you sure?')?>')) json_request('citizenship/cancel', {stateId: <?=$state->id?>})" class="btn btn-danger"><?=Yii::t('app', 'Fire citizenship')?></button>
                        <?php else: ?>
                            <button onclick="json_request('citizenship/request', {stateId: <?=$state->id?>})" class="btn btn-primary"><?=Yii::t('app', 'Make request for citizenship')?></button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
