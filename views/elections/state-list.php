<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\widgets\ElectionInfoWidget,
    app\models\politics\elections\Election;

/* @var $this yii\base\View */
/* @var $state State */
/* @var $new Election[] */
/* @var $all Election[] */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Elections in state {0}', [LinkCreator::stateLink($state)])?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active"><?=Yii::t('app', 'Elections')?></li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h4 class="box-title"><?=Yii::t('app', 'Active elections')?></h4>
        </div>
        <div class="box-body">
            <div class="box-group">
            <?php foreach ($new as $election): ?>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Elections of agency post {0}', [Html::encode($election->whom->name)])?></h3>
                </div>
                <div class="box-body">
                  <?= ElectionInfoWidget::widget(['election' => $election]) ?>
                </div>
            </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="box">
        <div class="box-header">
            <h4 class="box-title"><?=Yii::t('app', 'Archive elections')?></h4>
        </div>
        <div class="box-body">
            <div class="box-group">
            <?php foreach ($all as $election): ?>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?=Yii::t('app', 'Elections of agency post {0}', [Html::encode($election->whom->name)])?></h3>
                </div>
                <div class="box-body">
                  <?= ElectionInfoWidget::widget(['election' => $election]) ?>
                </div>
            </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<?=$this->render('_js')?>