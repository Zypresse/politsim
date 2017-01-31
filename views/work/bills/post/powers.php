<?php

use yii\helpers\ArrayHelper,
    app\models\politics\constitution\articles\postsonly\powers\Parties,
    app\models\politics\constitution\articles\postsonly\powers\Bills,
    app\models\economics\LicenseProto,
    app\models\politics\constitution\articles\postsonly\powers\Licenses;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[postId]')->dropDownList(ArrayHelper::map($post->state->posts, 'id', 'name'))->label(Yii::t('app', 'Agency post'))?>

<?=$form->field($model, 'dataArray[bills]')->checkboxList(Bills::getList())->label(Yii::t('app', 'Bills powers'))?>
<?=$form->field($model, 'dataArray[parties]')->checkboxList(Parties::getList())->label(Yii::t('app', 'Parties powers'))?>
<?=$form->field($model, 'dataArray[licenses][value]')->checkboxList(Licenses::getList())->label(Yii::t('app', 'Licenses powers'))?>
<?=$form->field($model, 'dataArray[licenses][value2]')->checkboxList(ArrayHelper::map(LicenseProto::getList(), 'id', 'name'))->label(Yii::t('app', 'Licenses management'))?>
