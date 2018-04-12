<?php

use app\helpers\Html;
use app\helpers\LinkCreator;

/* @var $this yii\base\View */
/* @var $approved app\models\government\Citizenship[] */
/* @var $requested app\models\government\Citizenship[] */
/* @var $user \app\models\auth\User */

?>
<section class="content-header">
    <h1>
        <?= Yii::t('app', 'Citizenships') ?>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> <a href="/user/profile"><?= Html::encode($user->name) ?></a></li>
        <li class="active"><?= Yii::t('app', 'Citizenships') ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h4><?= Yii::t('app', 'Citizenships') ?></h4>
                </div>
                <div class="box-body">
                    <?php if (count($approved)): ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'State') ?></th>
                                    <th><?= Yii::t('app', 'Date approved') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($approved as $citizenship): ?>
                                    <tr>
                                        <td>
                                            <?= LinkCreator::stateLink($citizenship->state) ?>
                                        </td>
                                        <td>
                                            <?= Html::timeAutoFormat($citizenship->dateApproved) ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>
                            <?= Yii::t('app', 'You have no one citizenship') ?><br>
                            <?= Yii::t('app', 'Use one of this pages to select state:') ?>
                        </p>
                        <div class="btn-group">
                            <a class="btn btn-primary" href="/rating/states">
                                <i class="fa fa-th-list"></i> <?= Yii::t('app', 'States chart') ?>
                            </a>
                            <a class="btn btn-info" href="/map/political">
                                <i class="fa fa-flag"></i> <?= Yii::t('app', 'Political map') ?>
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h4><?= Yii::t('app', 'Citizenship requests') ?></h4>
                </div>
                <div class="box-body">
                    <?php if (count($requested)): ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'State') ?></th>
                                    <th><?= Yii::t('app', 'Request date') ?></th>
                                    <th><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requested as $citizenship): ?>
                                    <tr>
                                        <td>
                                            <?= LinkCreator::stateLink($citizenship->state) ?>
                                        </td>
                                        <td>
                                            <?= Html::timeAutoFormat($citizenship->dateCreated) ?>
                                        </td>
                                        <td>
                                            <button onclick="json_request('citizenship/cancel', {stateId: <?= $citizenship->stateId ?>})" class="btn btn-danger btn-xs"><?= Yii::t('app', 'Cancel request') ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?= Yii::t('app', 'You have no one citizenship request') ?></p>
                    <?php endif ?>
                </div>
            </div>            
        </div>
    </div>
</section>