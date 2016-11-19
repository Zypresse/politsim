<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $city app\models\City */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($city->name)?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($city->region->state)?></li>
        <li><?=LinkCreator::regionLink($city->region)?></li>
        <li class="active"><?=$city->flag ? Html::img($city->flag, ['style' => 'height: 8px; vertical-align: baseline;']) : ''?> <?=Html::encode($city->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <?php if ($city->flag || $city->anthem): ?>
        <div class="col-md-4">
            <?php if ($city->flag): ?>
                <div class="box">
                    <div class="box-body">
                        <?=Html::img($city->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'City flag')?></em>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($city->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $city->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'City anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($city->flag || $city->anthem)?8:12?>">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($city->name)?>
                         <small>(<?=Html::encode($city->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-group"></i> <?=Yii::t('app', 'Population')?></strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($city->population)?> <?=Html::a(Yii::t('app', 'Population info'),'#!population/city&id='.$city->id,['class' => 'btn btn-info btn-xs'])?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>