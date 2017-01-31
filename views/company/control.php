<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $company app\models\economics\Company */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($company->name)?>
    </h1>
<!--    <ol class="breadcrumb">
        <li class="active"><?=$company->flag ? Html::img($company->flag, ['style' => 'height: 8px; vertical-align: baseline;']) : ''?> <?=Html::encode($company->name)?></li>
    </ol>-->
</section>
<section class="content">
    <div class="row">
        <?php if ($company->flag): ?>
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <?=Html::img($company->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'Company logo')?></em>
                </div>
            </div>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($company->flag)?8:12?>">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($company->name)?>
                         <small>(<?=Html::encode($company->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php if (!$company->state || $company->state->dateDeleted): ?>
                        <div class="callout callout-danger">
                            <h4><i class="icon fa fa-ban"></i> <?=Yii::t('app', 'Company registered in not-existing state!')?></h4>
                            <p><?= $company->state ? Yii::t('app', 'This company registered in state {0} but this state no more exist', [LinkCreator::stateLink($company->state)]) : Yii::t('app', 'This company registered in unknown state')?></p>
                        </div>
                        <?php endif ?>
                        <div class="col-md-6">
                            <p>
                                <strong><?=Yii::t('app', 'Director:')?></strong> <?= $company->director ? LinkCreator::userLink($company->director) : Yii::t('yii', '(not set)') ?>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Capitalization:')?></strong> <span class="status-success"><?=MyHtmlHelper::aboutNumber($company->capitalization)?> <?=MyHtmlHelper::icon('money')?></span>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Balance')?></strong>
                                <?= MyHtmlHelper::moneyFormat($company->getBalance(0)) ?>
                            </p>
                            <?php if ($company->isGoverment): ?>
                            <p>
                                <?=Yii::t('app', 'This is a goverment company')?>
                            </p>
                            <?php endif ?>
                            <?php if ($company->isHalfGoverment): ?>
                            <p>
                                <?=Yii::t('app', 'This is a half goverment company')?>
                            </p>
                            <?php endif ?>
                            <?php if ($company->state && !$company->state->dateDeleted): ?>
                            <p>
                                <?=Yii::t('app', 'Company registered in state {0}', [LinkCreator::stateLink($company->state)])?>
                            </p>
                            <?php endif ?>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?=Yii::t('app', 'Shares issued:')?></strong> <?= MyHtmlHelper::formateNumberword($company->sharesIssued,'s') ?> <?=MyHtmlHelper::icon('money')?>
                            </p>
                            <p>
                                <strong><?=Yii::t('app', 'Share price:')?></strong> <?= MyHtmlHelper::moneyFormat($company->sharesPrice) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-group"></i> <?=Yii::t('app', 'Shareholders')?>
                    </span>
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <thead>
                            <tr>
                                <td><?=Yii::t('app', 'Shareholder')?></td>
                                <td><?=Yii::t('app', 'Stake')?></td>
                            </tr>
                        </thead>
                        <?php foreach ($company->shares as $share): ?>
                            <tr>
                                <td><?= LinkCreator::link($share->master) ?></td>
                                <td>
                                    <?= MyHtmlHelper::formateNumberword($share->count, 's') ?> (<?= round(100*$share->count/$company->sharesIssued, 2) ?>%)
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-legal"></i> <?=Yii::t('app', 'Licenses')?>
                    </span>
<!--                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-success">
                            Получить лицензию
                        </button>
                    </div>-->
                </div>
                <div class="box-content">    
                    <table class="table table-normal">
                        <?php if (count($company->licenses)): ?>
                            <thead>
                                <tr>
                                    <td><?=Yii::t('app', 'License type')?></td>
                                    <td><?=Yii::t('app', 'State')?></td>
                                </tr>
                            </thead>
                            <?php foreach ($company->licenses as $license): ?>
                                <tr>
                                    <td><?= $license->proto->name ?></td>
                                    <td><?= LinkCreator::stateLink($license->state) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td><?=Yii::t('app', 'Company have no one license')?></td>
                            </tr>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>