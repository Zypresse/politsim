<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $region app\models\Region */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($region->name)?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($region->state)?></li>
        <li class="active"><?=$region->flag ? Html::img($region->flag, ['style' => 'height: 8px; vertical-align: baseline;']) : ''?> <?=Html::encode($region->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <?php if ($region->flag || $region->anthem): ?>
        <div class="col-md-4">
            <?php if ($region->flag): ?>
                <div class="box">
                    <div class="box-body">
                        <?=Html::img($region->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'Region flag')?></em>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($region->anthem): ?>
                <div class="box">
                    <div class="box-body">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $region->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'Region anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <div class="col-md-<?=($region->flag || $region->anthem)?8:12?>">            
            <div class="box">
                <div class="box-body">
                    <h1>
                        <?=Html::encode($region->name)?>
                         <small>(<?=Html::encode($region->nameShort)?>)</small>
                    </h1>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><i class="fa fa-building"></i> <?=Yii::t('app', 'Capital city')?></strong></td>
                                        <td>
                                            <?php if ($region->city): ?>
                                                <a href="#!city&id=<?=$region->cityId?>"><?=Html::encode($region->city->name)?></a>
                                                <?=LinkCreator::cityLink($region->city)?>
                                            <?php else: ?>
                                                <?=Yii::t('app', 'Not set')?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fa fa-group"></i> <?=Yii::t('app', 'Population')?></strong></td>
                                        <td><?=MyHtmlHelper::formateNumberword($region->population)?> <?=Html::a(Yii::t('app', 'Population info'),'#!population/region&id='.$region->id,['class' => 'btn btn-info btn-xs'])?></td>
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