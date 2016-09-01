<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $state app\models\State */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($state->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=Html::img($state->flag, ['style' => 'height: 8px; vertical-align: baseline;'])?> <?=Html::encode($state->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-content">
                    <?=Html::img($state->flag, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                </div>
                <div class="box-footer">
                    <em><?=Yii::t('app', 'National flag')?></em>
                </div>
            </div>
            <?php if ($state->anthem): ?>
                <div class="box">
                    <div class="box-content">
                        <iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=<?= $state->anthem ?>" width="100%" height="100" scrolling="no" frameborder="no"></iframe>
                    </div>
                    <div class="box-footer">
                        <em><?=Yii::t('app', 'National anthem')?></em>
                    </div>
                </div>
            <?php endif ?>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-content">
                    <h1>
                        <?=Html::encode($state->name)?>
                         <small>(<?=Html::encode($state->nameShort)?>)</small>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</section>