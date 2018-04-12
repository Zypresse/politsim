<?php

use app\helpers\Html;
use app\helpers\LinkCreator;

/* @var $this \yii\web\View */
/* @var $approved app\models\politics\OrganizationMembership[] */
/* @var $requested app\models\politics\OrganizationMembership[] */
/* @var $user \app\models\auth\User */

$this->title = 'Организации';

?>
<section class="content-header">
    <h1>
        Организации
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-user"></i> <a href="/user/profile"><?= Html::encode($user->name) ?></a></li>
        <li class="active">Организации</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h4>Организации</h4>
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
                                <?php foreach ($approved as $membership): ?>
                                    <tr>
                                        <td>
                                            <?= LinkCreator::orgLink($membership->org) ?>
                                        </td>
                                        <td>
                                            <?= Html::timeAutoFormat($membership->dateApproved) ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>
                            Вы не состоите ни в одной организации<br>
                            Выберите одну из существующих или создайте новую
                        </p>
                        <div class="btn-group">
                            <a class="btn btn-primary" href="/rating/organizations">
                                <i class="fa fa-th-list"></i> Рейтинг организация
                            </a>
                            <a class="btn btn-info" href="/organization/create">
                                <i class="fa fa-flag"></i> Создать организацию
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h4>Заявки на членство в организациях</h4>
                </div>
                <div class="box-body">
                    <?php if (count($requested)): ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Организация</th>
                                    <th><?= Yii::t('app', 'Request date') ?></th>
                                    <th><?= Yii::t('app', 'Action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requested as $membership): ?>
                                    <tr>
                                        <td>
                                            <?= LinkCreator::orgLink($membership->org) ?>
                                        </td>
                                        <td>
                                            <?= Html::timeAutoFormat($membership->dateCreated) ?>
                                        </td>
                                        <td>
                                            <button onclick="json_request('organization/cancel-membership', {orgId: <?= $membership->orgId ?>})" class="btn btn-danger btn-xs"><?= Yii::t('app', 'Cancel request') ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Вы не подавали новых заявок на членство в организациях</p>
                    <?php endif ?>
                </div>
            </div>            
        </div>
    </div>
</section>
