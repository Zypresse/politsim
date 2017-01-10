<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\constitution\ConstitutionArticleType;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box-group">
            <?php foreach ($user->posts as $post): ?>
            <?php $powersBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS_BILLS)->selected; ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?=Html::encode($post->name)?></h3>
                        <div class="box-tools pull-right">
                            <?=LinkCreator::stateLink($post->state)?>
                        </div>
                    </div>
                    <div class="box-body">
                        <h4><?=Yii::t('app', 'Bills powers')?>:</h4>
                        <ul>
                        <?php foreach ($powersBills as /*$val =>*/ $name): ?>
                            <li><?=$name?></li>
                        <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group">
                            
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</section>