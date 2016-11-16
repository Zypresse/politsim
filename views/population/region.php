<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $region \app\models\Region */

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
            <ul>
                <?php foreach($region->state->regions as $currentRegion): ?>
                <li <?=$currentRegion->id == $region->id?'class="active"':''?>>
                    <?=LinkCreator::regionPopulationLink($currentRegion)?>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population of {0}', [$region->name])?>
                    </h2>
                </div>
                <div class="box-body">
                    <p>
                        <strong><?=Yii::t('app', 'Population')?>:</strong>
                        <?=MyHtmlHelper::formateNumberword($region->population, 'h')?>
                    </p>
                    <p>
                        <strong><?=Yii::t('app', 'Nations')?></strong>
                        <?=$region->nations?>
                    </p>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population groups')?>
                    </h2>
                </div>
                <div class="box-body">
                    
                </div>
            </div>
        </div>
    </div>
</section>
    