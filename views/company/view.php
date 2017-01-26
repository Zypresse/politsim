<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $company app\models\economics\Company */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($company->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=$company->flag ? Html::img($company->flag, ['style' => 'height: 8px; vertical-align: baseline;']) : ''?> <?=Html::encode($company->name)?></li>
    </ol>
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
</section>