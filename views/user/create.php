<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\auth\User;
use kartik\widgets\FileInput;

/* @var $this \yii\web\View */
/* @var $model \app\models\auth\User */

$this->title = 'Создание персонажа';

?>
<section class="content-header">
    <h1>
        Создание персонажа
    </h1>
</section>
<section class="content">
    <!-- Default box -->
    <div class="box">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]) ?>
        <div class="box-body">
            <?= $form->field($model, 'name') ?>
            <div class="icheck">
                <?= $form->field($model, 'gender', ['options' => ['class' => 'form-group form-inline']])->radioList(User::gendersList(), ['value' => User::GENDER_MALE]) ?>
            </div>
            <?= $form->field($model, 'avatarFile')->widget(FileInput::class, [
                'options'=>[
                    'multiple' => false,
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'showRemove' => false,
                    'showUpload' => false,
                    'showCaption' => false,
                    'browseClass' => 'btn btn-primary btn-flat',
                    'browseIcon' => '<i class="fa fa-camera"></i> ',
                    'browseLabel' =>  'Выбрать фотографию',
                ],
            ]) ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= Html::submitButton('Создать персонажа', ['class' => 'btn btn-flat btn-success']) ?>
        </div>
        <!-- /.box-footer-->
        <?php ActiveForm::end() ?>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
