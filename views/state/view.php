<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $state app\models\State */
/* @var $user app\models\User */

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
                                        <td><a class="btn btn-info btn-xs btn-block" href="#!state/population&id=<?=$state->id?>"><?=MyHtmlHelper::formateNumberword($state->population)?></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>