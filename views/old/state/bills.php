<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $state app\models\politics\State */
/* @var $billsActive app\models\politics\bills\Bill[] */
/* @var $billsFinished app\models\politics\bills\Bill[] */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($state->name)?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($state)?></li>
        <li class="active"><?=Yii::t('app', 'Bills')?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Active bills') ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Bill creator') ?></th>
                                <th><?= Yii::t('app', 'Date Created') ?></th>
                                <th><?= Yii::t('app', 'Bill content') ?></th>
                                <th><?= Yii::t('app', 'Votes') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($billsActive as $bill): ?>
                                <tr>
                                    <td>
                                        <?php if ($bill->user || $bill->post): ?>
                                            <?php if ($bill->user): ?>
                                                <?= LinkCreator::userLink($bill->user) ?><br>
                                            <?php endif ?>
                                            <?php if ($bill->post): ?>
                                                <?= Html::encode($bill->post->name) ?>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <span class="formatDate" data-unixtime="<?= $bill->dateCreated ?>" ><?= date('H:M d.m.Y', $bill->dateCreated) ?></span>
                                    </td>
                                    <td><?= $bill->render() ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-green"><?= $bill->votesPlus ?></span>&nbsp;/&nbsp;<span class="badge bg-red"><?= $bill->votesMinus ?></span>&nbsp;/&nbsp;<span class="badge bg-gray"><?= $bill->votesAbstain ?></span>
                                    </td>
                                    <td>
                                        <?= Html::a(Yii::t('app', 'Open bill info'), ['#!bills/view', 'id' => $bill->id], ['class' => 'btn btn-primary']) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title"><?= Yii::t('app', 'Finished bills') ?></h4>
                </div>
                <div class="box-body">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Bill creator') ?></th>
                                <th><?= Yii::t('app', 'Date Created') ?></th>
                                <th><?= Yii::t('app', 'Date Finished') ?></th>
                                <th><?= Yii::t('app', 'Bill content') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($billsFinished as $bill): ?>
                                <tr>
                                    <td>
                                        <?php if ($bill->user || $bill->post): ?>
                                            <?php if ($bill->user): ?>
                                                <?= LinkCreator::userLink($bill->user) ?><br>
                                            <?php endif ?>
                                            <?php if ($bill->post): ?>
                                                <?= Html::encode($bill->post->name) ?>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <span class="formatDate" data-unixtime="<?= $bill->dateCreated ?>" ><?= date('H:M d.m.Y', $bill->dateCreated) ?></span>
                                    </td>
                                    <td>
                                        <span class="formatDate" data-unixtime="<?= $bill->dateFinished ?>" ><?= date('H:M d.m.Y', $bill->dateFinished) ?></span>
                                    </td>
                                    <td><?= $bill->render() ?></td>
                                    <td class="text-center">
                                        <?= $bill->isApproved ? '<span class="badge bg-green">'.Yii::t('app', 'Accepted').'</span>' : '<span class="badge bg-red">'.Yii::t('app', 'Declined').'</span>' ?>
                                    </td>
                                    <td>
                                        <?= Html::a(Yii::t('app', 'Open bill info'), ['#!bills/view', 'id' => $bill->id], ['class' => 'btn btn-primary']) ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
