<?php

use yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $list app\models\Citizenship[] */
/* @var $user \app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Citizenships')?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> <a href="#!profile"><?=Html::encode($user->name)?></a></li>
        <li class="active"><?=Yii::t('app', 'Citizenships')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <?php if (count($list)): ?>
                <?php foreach ($list as $citizenship): ?>
                <?=$citizenship->dateCreated?>
                <?php endforeach ?>
            <?php else: ?>
            <h3><?=Yii::t('app', 'You have no one citizenship')?></h3>
            <p><?=Yii::t('app', 'Use one of this pages to select state:')?>
            <a class="btn btn-primary" href="#!chart/states">
                <i class="fa fa-th-list"></i> <?=Yii::t('app', 'States chart')?>
            </a>
            <a class="btn btn-info" href="#!map">
                <i class="fa fa-flag"></i> <?=Yii::t('app', 'Political map')?>
            </a></p>
            <?php endif ?>
        </div>
    </div>
</section>