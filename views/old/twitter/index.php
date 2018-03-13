<?php

use app\components\LinkCreator,
    yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $user app\models\User */
/* @var $profile app\models\TwitterProfile */

?>
<section class="content-header">
    <h1>
        <?= Yii::t('app', 'Social network') ?>
    </h1>
    <ol class="breadcrumb">
        <li><?= LinkCreator::userLink($user) ?></li>
        <li class="active"><?= Yii::t('app', 'Feed') ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-widget widget-user">
                <div class="widget-user-header bg-blue-active">
                    <h3 class="widget-user-username"><a href="#!profile&id=<?=$user->id?>"><?=Html::encode($user->name)?></a></h3>
                    <h5 class="widget-user-desc"><a class="text-gray" href="#!/twitter/profile?id=<?=$user->id?>">@<?= $profile->nickname ?></a></h5>
                </div>
                <div class="widget-user-image">
                    <a href="#!profile&id=<?=$user->id?>"><?=Html::img(Html::encode($user->avatar),['class' => 'img-circle'])?></a>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><?= number_format($profile->followersCount, 0, '', ' ') ?></h5>
                                <span class="description-text"><i class="fa fa-group"></i> <?=Yii::t('app', 'Followers') ?></span>
                            </div>
                        </div>
<!--                        <div class="col-xs-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><? ?></h5>
                                <span class="description-text"><? ?></span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="description-block">
                                <h5 class="description-header"><? ?></h5>
                                <span class="description-text"><? ?></span>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    <?php                    var_dump($profile->feed) ?>
                </div>
            </div>
        </div>
    </div>
</section>
