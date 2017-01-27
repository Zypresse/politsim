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
/* @var $region \app\models\politics\Region */

?>
<section class="content-header">
    <h1>
        <?=$region->flag ? Html::img($region->flag, ['style' => 'height:20px']) : ''?> <?=Html::encode($region->name)?>
    </h1>    
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($region->state)?></li>
        <li><?=LinkCreator::regionLink($region)?></li>
        <li class="active"><?=Yii::t('app', 'Population')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <?=PopInfoMenuWidget::widget(['activeRegion' => $region])?>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population of region {0}', [$region->name])?>
                    </h2>
                    <div class="box-tools pull-right">
                        <?=MyHtmlHelper::formateNumberword($region->population, 'h')?>
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
                                    <?= GendersPieChartWidget::widget(['data' => $region->genders]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Classes')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= ClassesPieChartWidget::widget(['data' => $region->classes]) ?>
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
                                    <?= NationsPieChartWidget::widget(['data' => $region->nations]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?=Yii::t('app', 'Religions')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <?= ReligionsPieChartWidget::widget(['data' => $region->religions]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population groups')?>
                    </h2>
                </div>
                <div class="box-body">
                    
                </div>
            </div>-->
        </div>
    </div>
</section>
    