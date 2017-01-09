<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Your posts')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::userLink($user)?></li>
        <li class="active"><?=Yii::t('app', 'Posts')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box-group">
                <?php foreach ($user->states as $state): ?>
                <div class="box box-default">
                    <div class="box-header">
                        <h4 class="box-title"><?=LinkCreator::stateLink($state)?></h4>
                    </div>
                    <div class="box-body">
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th><?=Yii::t('app', 'Post name')?></th>
                                    <th><?=Yii::t('app', 'Agency')?></th>
                                    <th><?=Yii::t('app', 'State')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user->getPostsByState($state->id)->with('agencies')->with('state')->all() as $post): ?>
                                <tr>
                                    <td><?=Html::encode($post->name)?></td>
                                    <td>
                                    <?php if (count($post->agencies)): ?>
                                        <?php foreach ($post->agencies as $agency): ?>
                                        <?=LinkCreator::agencyLink($agency)?><br>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <?=Yii::t('yii', '(not set)')?>
                                    <?php endif ?>
                                    </td>
                                    <td><?=LinkCreator::stateLink($post->state)?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>