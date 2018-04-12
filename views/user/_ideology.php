<?php

use app\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $ideologies app\models\variables\Ideology[] */
/* @var $user app\models\auth\User */

?>
<?php $form = ActiveForm::begin([
    'action' => ['/user/ideology'],
    'method' => 'POST',
]) ?>
<div class="form-group">
    <label for="#new-ideology-id"><?=Yii::t('app', 'New ideology')?></label>
    <?= Html::dropDownList('id', $user->ideologyId, ArrayHelper::map($ideologies, 'id', 'name'), ['id' => 'new-ideology-id', 'class' => 'form-control']) ?>
</div>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-flat']) ?>
</div>
<?php ActiveForm::end() ?>