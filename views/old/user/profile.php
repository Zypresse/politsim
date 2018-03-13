<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this \yii\web\View */
/* @var $user \app\models\User */
/* @var $isOwner boolean */

$viewer = Yii::$app->user->identity;

?>

<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class=" box" >
                <div class="box-body">
                    <?=Html::img($user->avatarBig, ['class' => 'img-polaroid', 'style' => 'width: 100%'])?>
                    <div class="photo_bottom_container">
                        <span class="star" ><?= $user->fame ?> <?= MyHtmlHelper::icon('star') ?></span>
                        <span class="heart" ><?= $user->trust?> <?= MyHtmlHelper::icon('heart') ?></span>
                        <span class="chart_pie" ><?= $user->success ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('app', 'Modifiers')?></h3>
                </div>
                <div class="box-body">
                    <?php foreach ($user->modifiers as $modifier): ?>
                    <div class="modifier-box ">
                        <span class="modifier-box-icon">
                            <?=Html::img($modifier->icon, ['title' => $modifier->name])?>
                        </span>
                        <div class="modifier-box-content">
                            <span class="modifier-box-text">
                                <div class="pull-right">
                                <?php if ($modifier->fame): ?>
                                    <span class="star"><?=($modifier->fame>0?'+':'').$modifier->fame.' '.MyHtmlHelper::icon('star', '')?></span>
                                <?php endif ?>
                                <?php if ($modifier->trust): ?>
                                    <span class="heart"><?=($modifier->trust>0?'+':'').$modifier->trust.' '.MyHtmlHelper::icon('heart', '')?></span>
                                <?php endif ?>
                                <?php if ($modifier->success): ?>
                                    <span class="chart_pie"><?=($modifier->success>0?'+':'').$modifier->success.' '.MyHtmlHelper::icon('chart_pie', '')?></span>
                                <?php endif ?>
                                </div>
                            </span>
                        </div>
                    </div>
                    
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box">
                <div class="box-header">
                    <h1><?= Html::encode($user->name) ?> <?php if ($isOwner): ?><small>(это вы)</small><?php endif ?></h1>
                </div>
                <div class="box-body">
                    <?php if ($user->ideology):  ?>
                    <p>
                        <i class="fa fa-flag"></i>
                        <?php if ($user->ideology) : ?>
                            <?=Yii::t('app', 'Have ideology «{0}»', [$user->ideology->name])?>
                        <?php endif ?>
                    </p>
                    <?php endif ?>
                    <?php if ($user->religion):  ?>
                    <p>
                        <i class="fa">☪</i>
                        <?php if ($user->religion) : ?>
                            <?=Yii::t('app', 'Have religion «{0}»', [$user->religion->name])?>
                        <?php endif ?>
                    </p>
                    <?php endif ?>
                    <p>
                        <i class="fa fa-group"></i>
                        <?php if (count($user->parties)): ?>
                            <?php
                                $partyLinks = [];
                                foreach ($user->parties as $party) {
                                    $partyLinks[] = LinkCreator::partyLink($party);
                                }
                            ?>
                            <?=Yii::t('app', 'Have parties membership: {0}', [implode(', ', $partyLinks)])?>
                        <?php else: ?>
                            <?=Yii::t('app', 'Have not party membership')?>
                        <?php endif ?>
                    </p>
                    <p>
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
                    </p>            
                    <p>                
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
                    </p>
                    <p>
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
                    </p>
                </div>
                <div class="box-footer">
                <?php if ($isOwner): ?>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <button id="choose-ideology-btn" class="btn btn-sm btn-primary"><i class="fa fa-flag"></i> &nbsp; <?=Yii::t('app', 'Change ideology')?></button>
                            <button id="choose-religion-btn" class="btn btn-sm btn-primary"><i class="fa">☪</i> &nbsp; <?=Yii::t('app', 'Change religion')?></button>
                        </div>
                    </div>
                <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    
    function chooseIdeology() {
        var buttons = '<button class="btn btn-primary" onclick="json_request(\'user/save-ideology\',{ideologyId:$(\'#new-ideology-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('user/choose-ideology', {}, 
            '<?=Yii::t('app', 'Choose your new ideology')?>',
            buttons
        );
    }
    $('#choose-ideology-btn').click(chooseIdeology);
    
    function chooseReligion() {
        var buttons = '<button class="btn btn-primary" onclick="json_request(\'user/save-religion\',{religionId:$(\'#new-religion-id\').val()})"><?=Yii::t('app', 'Save')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Cancel')?></button>';
        createAjaxModal('user/choose-religion', {}, 
            '<?=Yii::t('app', 'Choose your new religion')?>',
            buttons
        );
    }
    $('#choose-religion-btn').click(chooseReligion);

</script>
