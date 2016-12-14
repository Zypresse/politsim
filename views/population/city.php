<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\components\widgets\PopInfoMenuWidget;

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
            <?=PopInfoMenuWidget::widget(['activeCity' => $city])?>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population of city {0}', [$city->name])?>
                    </h2>
                    <div class="box-tools pull-right">
                        <?=MyHtmlHelper::formateNumberword($city->population, 'h')?>
                    </div>
                </div>
                <div class="box-body">
                    <pre>
                        <?php var_dump($city->attributes) ?>
                    </pre>
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
    