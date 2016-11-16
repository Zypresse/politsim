<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $city \app\models\City */

?>
<section class="content-header">
    <h1>
        <?=$city->flag ? Html::img($city->flag, ['style' => 'height:20px']) : ''?> <?=Html::encode($city->name)?>
    </h1>    
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($city->region->state)?></li>
        <li><?=LinkCreator::regionLink($city->region)?></li>
        <li><?=LinkCreator::cityLink($city)?></li>
        <li class="active"><?=Yii::t('app', 'Population')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <ul>
                <?php foreach($city->region->state->regions as $currentRegion): ?>
                <li <?=$currentRegion->id == $city->region->id?'class="active"':''?>>
                    <?=LinkCreator::regionPopulationLink($currentRegion)?>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population of {0}', [$city->name])?>
                    </h2>
                </div>
                <div class="box-body">
                    <p>
                        <strong><?=Yii::t('app', 'Population')?>:</strong>
                        <?=MyHtmlHelper::formateNumberword($city->population, 'h')?>
                    </p>
                    <p>
                        <strong><?=Yii::t('app', 'Nations')?></strong>
                        <?=$city->nations?>
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
    