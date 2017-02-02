<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this \yii\web\View */
/* @var $building \app\models\economics\units\Unit */
/* @var $user \app\models\User */

?>
<section class="content-header">
    <h1>
        <?= Html::encode($building->name) ?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::link($building->master) ?></li>
        <li class="active"><?= Html::encode($building->name) ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?= Html::encode($building->name) ?>
                        <small>(<?= Html::encode($building->proto->name) ?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($building->dateDeleted): ?>
                                <div class="callout callout-danger">
                                    <h4><i class="icon fa fa-ban"></i> <?= Yii::t('app', 'Firm deleted!') ?></h4>
                                    <p><?= Yii::t('app', 'Firm was deleted at {0}', [MyHtmlHelper::timeAutoFormat($building->dateDeleted)]) ?></p>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?= Yii::t('app', 'Firm master:') ?></strong>
                                <?= LinkCreator::link($building->master) ?>
                            </p>
                            <p>
                                <strong><?= Yii::t('app', 'Manager:') ?></strong>
                                <?= $building->manager ? LinkCreator::userLink($building->manager) : Yii::t('yii', '(not set)') ?>
                            </p>
                            <?php if ($building->tile): ?>
                                <p>
                                    <strong><?= Yii::t('app', 'Current location:') ?></strong>
                                    <?= $building->city ? LinkCreator::cityLink($building->city) : LinkCreator::regionLink($building->region) ?>
                                </p>
                            <?php endif ?>
                            <p>
                                <strong><?= Yii::t('app', 'Firm size:') ?></strong>
                                <?= $building->size ?> <i class="fa fa-balance-scale"></i>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><?= Yii::t('app', 'Status:') ?></strong>
                                <?= $building->status->name ?>
                            </p>
                            <p>
                                <strong><?= Yii::t('app', 'Current task:') ?></strong>
                                <?= $building->taskId ?>
                                <?= $building->taskSubId ?>
                                <?= $building->taskFactor ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($building->isUserController($user->id)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Actions') ?></h4>
                </div>
                <div class="box-body">
                    
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</section>
