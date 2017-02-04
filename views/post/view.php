<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($post->name)?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($post->state)?></li>
        <li class="active"><?=Html::encode($post->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">            
            <div class="box">
                <div class="box-header">
                    <h1>
                        <?=Html::encode($post->name)?>
                         <small>(<?=Html::encode($post->nameShort)?>)</small>
                    </h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered no-margin">
                                <tbody>
                                    <tr>
                                        <td><strong><?=Yii::t('app', 'User')?></strong></td>
                                        <td>
                                            <?= $post->user ? LinkCreator::userLink($user) : Yii::t('yii', '(not set)') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><?=Yii::t('app', 'Agencies')?></strong></td>
                                        <td>
                                        <?php foreach ($post->agencies as $agency): ?>
                                            <?= LinkCreator::agencyLink($agency) ?><br>
                                        <?php endforeach?>
                                        </td>
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