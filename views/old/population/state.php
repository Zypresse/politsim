<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\components\widgets\PopInfoMenuWidget,
    app\components\widgets\NationsPieChartWidget,
    app\components\widgets\GendersPieChartWidget,
    app\components\widgets\ClassesPieChartWidget,
    app\components\widgets\ReligionsPieChartWidget;

/* @var $this \yii\web\View */
/* @var $state \app\models\politics\State */

?>
<section class="content-header">
    <h1>
        <?= Html::img($state->flag, ['style' => 'height:20px']) ?> <?= Html::encode($state->name) ?>
    </h1>    
    <ol class="breadcrumb">
        <li><?= LinkCreator::stateLink($state) ?></li>
        <li class="active"><?= Yii::t('app', 'Population') ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <?= PopInfoMenuWidget::widget(['activeState' => $state]) ?>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?= Yii::t('app', 'Population of state {0}', [$state->name]) ?>
                    </h2>
                    <div class="box-tools pull-right">
                        <?= MyHtmlHelper::formateNumberword($state->population, 'h') ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Genders structure')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= GendersPieChartWidget::widget(['data' => $state->genders]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Classes')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= ClassesPieChartWidget::widget(['data' => $state->classes]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Nations')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= NationsPieChartWidget::widget(['data' => $state->nations]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Religions')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= ReligionsPieChartWidget::widget(['data' => $state->religions]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
