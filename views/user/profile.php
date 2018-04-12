<?php

use yii\bootstrap\Html;
use app\helpers\Icon;

/* @var $this \yii\web\View */
/* @var $model \app\models\auth\User */
/* @var $viewer \app\models\auth\User */
/* @var $isOwner boolean */

$this->title = $model->name;

?>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class=" box" >
                <div class="box-body">
                    <?=Html::img($model->avatarBig, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    <div class="photo_bottom_container">
                        <span class="star" ><?= $model->fame ?> <?= Icon::draw(Icon::STAR) ?></span>
                        <span class="heart" ><?= $model->trust?> <?= Icon::draw(Icon::HEART) ?></span>
                        <span class="chart_pie" ><?= $model->success ?> <?= Icon::draw(Icon::CHARTPIE) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h1><?= Html::encode($model->name) ?> <?php if ($isOwner): ?><small>(это вы)</small><?php endif ?></h1>
                </div>
                <div class="box-body">
                    <p>
                        <i class="fa fa-flag"></i>
                        <?php if ($model->ideology) : ?>
                            <?=Yii::t('app', 'Have ideology «{0}»', [$model->ideology->name])?>
                        <?php else: ?>
                        Идеология не указана
                        <?php endif ?>
                        <?php if ($isOwner): ?>
                        <button id="choose-ideology-btn" class="btn btn-xs btn-primary">Изменить</button>
                        <?php endif ?>
                    </p>
                    <?php /*<p>
                        <i class="fa fa-group"></i>
                        <?php if (count($model->parties)): ?>
                            <?php
                                $partyLinks = [];
                                foreach ($model->parties as $party) {
                                    $partyLinks[] = LinkCreator::partyLink($party);
                                }
                            ?>
                            <?=Yii::t('app', 'Have parties membership: {0}', [implode(', ', $partyLinks)])?>
                        <?php else: ?>
                            <?=Yii::t('app', 'Have not party membership')?>
                        <?php endif ?>
                    </p> */ ?>
                    <?php /*<p>
                        <i class="fa fa-globe"></i>
                        <?php if (count($user->states)): ?>
                            <?php
                                $stateLinks = [];
                                foreach ($user->states as $state) {
                                    $stateLinks[] = LinkCreator::stateLink($state);
                                }
                            ?>
                            <?=Yii::t('app', 'Have citizenships: {0}', [implode(', ', $stateLinks)])?>
                        <?php else: ?>
                            <?=Yii::t('app', 'Have not citizenship')?>
                        <?php endif ?>
                    </p> */ ?>
                    <?php /*<p>                
                        <i class="fa fa-briefcase"></i>
                        <?php if ($user->getPosts()->count()): ?>
                            <?php
                                $postsLinksByState = [];
                                foreach ($user->getPosts()->with('state')->all() as $post) {
                                    $postLink = Html::encode($post->name);
                                    if (isset($postsLinksByState[$post->stateId])) {
                                        $postsLinksByState[$post->stateId]['posts'][] = $postLink;
                                    } else {
                                        $postsLinksByState[$post->stateId] = [
                                            'link' => LinkCreator::stateLink($post->state),
                                            'posts' => [$postLink],
                                        ];
                                    }
                                }
                            ?>
                            
                            <?=Yii::t('app', 'Have agency posts: ')?><br>
                            <?php foreach ($postsLinksByState as $obj): ?>
                                <?=$obj['link']?>: <?=implode(',',$obj['posts'])?><br>
                            <?php endforeach ?>
                        <?php else: ?>
                            <?=Yii::t('app', 'Have not agency posts')?>
                        <?php endif ?>
                    </p> */ ?>
                    <?php /*<p>
                        <i class="fa fa-globe"></i>
                        <?php if ($user->tile): ?>
                            <?php if ($user->tile->city): ?>
                            <?= Yii::t('app', 'Have residence in city {0}', [LinkCreator::cityLink($user->tile->city)]) ?>
                            <?php elseif ($user->tile->region): ?>
                            <?= Yii::t('app', 'Have residence in region {0}', [LinkCreator::regionLink($user->tile->region)]) ?>
                            <?php else: ?>
                            <?= Yii::t('app', 'Have residence in tile [{0},{1}]', [$user->tile->x, $user->tile->y]) ?>
                            <?php endif ?>
                        <?php else: ?>
                            <?= Yii::t('app', 'Have not residence') ?>
                        <?php endif ?>
                    </p> */ ?>
                </div>
                <div class="box-footer">
                <?php if ($isOwner): ?>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            
                        </div>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>
