<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\politics\constitution\articles\postsonly\powers\Parties;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */

?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box-group">
            <?php foreach ($user->posts as $post): ?>
            <?php 
                /* @var $powersBills Bills */
                $powersBills = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::BILLS); 
                /* @var $powerParties Parties */
                $powersParties = $post->constitution->getArticleByType(ConstitutionArticleType::POWERS, Powers::PARTIES); 
            ?>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?=Html::encode($post->name)?></h3>
                        <div class="box-tools pull-right">
                            <?=LinkCreator::stateLink($post->state)?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-group">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title"><?=Yii::t('app', 'Powers')?></h4>
                                </div>
                                <div class="box-body">
                                    <h4><?=Yii::t('app', 'Bills powers')?>:</h4>
                                    <ul>
                                    <?php foreach ($powersBills->selected as /*$val =>*/ $name): ?>
                                        <li><?=$name?></li>
                                    <?php endforeach ?>
                                    </ul>
                                    <h4><?=Yii::t('app', 'Parties powers')?>:</h4>
                                    <ul>
                                    <?php foreach ($powersParties->selected as /*$val =>*/ $name): ?>
                                        <li><?=$name?></li>
                                    <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                            <?php if ($powersBills->value > 0): ?>
                                <?=$this->render('boxes/bills', [
                                    'post' => $post,
                                    'powersBills' => $powersBills,
                                ])?>
                            <?php endif ?>
                            <?php if ($powersParties->value > 0): ?>
                                <?=$this->render('boxes/parties', [
                                    'post' => $post,
                                    'powersParties' => $powersParties,
                                ])?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
