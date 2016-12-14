<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $state \app\models\State */

?>
<section class="content-header">
    <h1>
        <?=Html::img($state->flag, ['style' => 'height:20px'])?> <?=Html::encode($state->name)?>
    </h1>    
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active"><?=Yii::t('app', 'Population')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <ul>
                <?php foreach($state->regions as $region): ?>
                <li>
                    <?=LinkCreator::regionPopulationLink($region)?>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h2 class="box-title">
                        <?=Yii::t('app', 'Population of state {0}', [$state->name])?>
                    </h2>
                    <div class="box-tools pull-right">
                        <?=MyHtmlHelper::formateNumberword($state->population, 'h')?>
                    </div>
                </div>
                <div class="box-body">
                    <pre>
                        <?php var_dump($state->attributes) ?>
                    </pre>
                </div>
            </div>
        </div>
    </div>
</section>
    