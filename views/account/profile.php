<?php

use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $model \app\models\auth\Account */

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
            <?php foreach($model->users as $user): ?>
            <p>
                <?= Html::img($user->avatar, ['style' => 'width: 50px']) ?>
                <?= Html::a($user->name, ["user/profile", "id" => $user->id]) ?>
            </p>
            <?php endforeach ?>
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
</section>
<!-- /.content -->
