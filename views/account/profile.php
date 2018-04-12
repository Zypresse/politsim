<?php

use yii\bootstrap\Html;
use app\helpers\Icon;
use app\widgets\SocialAuth;

/* @var $this \yii\web\View */
/* @var $model \app\models\auth\Account */

$this->title = 'Настройки аккаунта';

?>
<section class="content-header">
    <h1>
        Настройки аккаунта
        <small><?= $model->email ?></small>
    </h1>
</section>
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Ваши персонажи</h3>
<!--            <div class="box-tools">
                <small>3 / 3 персонажей доступно</small>
            </div>-->
        </div>
        <div class="box-body">
        <?php if (count($model->users)): ?>
            <table class="table table-bordered table-hover">
            <?php foreach($model->users as $user): ?>
                <tr>
                    <td>
                        <?= Html::a(Html::img($user->avatar, ['style' => 'width: 16px']).'&nbsp;'.Html::encode($user->name), ["user/profile", "id" => $user->id]) ?>
                    </td>
                    <td>
                        <span class="star"><span class="autoupdated-fame"><?= $user->fame ?></span> <?= Icon::draw(Icon::STAR) ?></span>
                        <span class="heart"><span class="autoupdated-trust"><?= $user->trust ?></span> <?= Icon::draw(Icon::HEART) ?></span>
                        <span class="chart_pie"><span class="autoupdated-success"><?= $user->success ?></span> <?= Icon::draw(Icon::CHARTPIE) ?></span>
                    </td>
                    <td>
                    <?php if (Yii::$app->user->identity->activeUserId === $user->id): ?>
                        Сейчас используется этот персонаж
                    <?php else: ?>
                        <?= Html::a('<i class="fa fa-check"></i> Выбрать', ["user/select", "id" => $user->id], ['class' => 'btn btn-flat btn-primary']) ?>
                    <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </table>
        <?php else: ?>
            У вас пока нет ни одного персонажа
        <?php endif ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= Html::a('Создать персонажа', ['user/create'], ['class' => 'btn btn-flat btn-success']) ?>
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Способы входа</h3>
        </div>
        <div class="box-body">
            <?php foreach ($model->providers as $provider): ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    Провайдер #<?= $provider->sourceType?>, Аккаунт <?= $provider->sourceId ?>
                </div>
            </div>
            <?php endforeach ?>
            <div class="panel panel-info">
                <div class="panel-body">
                    Пароль <?= $model->password ? '<span class="text-green">установлен</span>' : '<span class="text-red">не установлен</span>' ?>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <p>Добавить способ входа:</p>
            <?= SocialAuth::widget([
                'inline' => true,
            ]) ?>
        </div>
    </div>
</section>
<!-- /.content -->
