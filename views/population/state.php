<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\components\widgets\PopInfoMenuWidget,
    app\components\widgets\NationsPieChartWidget;

/* @var $this \yii\web\View */
/* @var $state \app\models\State */

$nations = json_decode($state->nations, true);
uasort($nations, function($a,$b){return $b <=> $a;});

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
                                    <h3 class="box-title"><?=Yii::t('app', 'Nations')?></h3>
                                </div>
                                <div class="box-body text-center">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?=NationsPieChartWidget::widget(['data' => $nations])?>
                                        </div>
                                        <div class="col-md-8">
                                            <table class="table table-bordered table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th><?=Yii::t('app', 'Nation')?></th>
                                                        <th><?=Yii::t('app', 'Percents')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($nations as $id => $percents): ?>
                                                    <?php $nation = \app\models\Nation::findOne($id); ?>
                                                    <tr>
                                                        <td style="width: 30px; background-color: <?=$nation->color?>"></td>
                                                        <td><?=$nation->name?></td>
                                                        <td><?=$percents?>%</td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <pre>
                        <?php var_dump($state->attributes) ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
</section>
