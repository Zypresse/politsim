<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\politics\Organization;
use kartik\widgets\FileInput;

/* @var $this \yii\web\View */
/* @var $model \app\models\politics\Organization */

$this->title = 'Создание организации';

?>
<section class="content-header">
    <h1>
        Создание организации
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
            <?= $form->field($model, 'nameShort') ?>
            <?= $form->field($model, 'flagFile')->widget(FileInput::class, [
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
            <?= $form->field($model, 'anthem') ?>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= Html::submitButton('Создать организацию', ['class' => 'btn btn-flat btn-success']) ?>
        </div>
        <!-- /.box-footer-->
        <?php ActiveForm::end() ?>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
