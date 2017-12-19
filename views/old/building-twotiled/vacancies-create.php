<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\components\MyHtmlHelper,
    app\models\economics\resources\Currency,
    app\models\population\PopClass;

/* @var $this \yii\web\View */
/* @var $building \app\models\economics\units\BuildingTwotiled */
/* @var $user \app\models\User */
/* @var $model \app\models\economics\units\Vacancy */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'new-vacancy-form',
    ],
    'action' => Url::to(['building-twotiled/vacancy-create-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['building-twotiled/vacancy-create-form', 'id' => $building->id])
]) ?>

<?=$form->field($model, 'objectId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'popClassId')->dropDownList(ArrayHelper::map(PopClass::findAll(), 'id', 'name'), [])->label(Yii::t('app', 'Population class')) ?>
<?=$form->field($model, 'countAll')->textInput(['type' => 'number'])->label(Yii::t('app', 'Count all')) ?>
<?=$form->field($model, 'currencyId')->dropDownList(ArrayHelper::map(Currency::findAll(), 'id', 'name'))->label(Yii::t('app', 'Currency')) ?>
<?=$form->field($model, 'wage')->textInput(['type' => 'number'])->label(Yii::t('app', 'Wage').' '.MyHtmlHelper::icon('money', '')) ?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-vacancy-form');
    
    $form.yiiActiveForm('add', {
        'id': 'vacancy-popclassid',
        'name': 'Vacancy[popClassId]',
        'container': '.field-vacancy-popclassid',
        'input': '#vacancy-popclassid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'vacancy-countall',
        'name': 'Vacancy[countAll]',
        'container': '.field-vacancy-countall',
        'input': '#vacancy-countall',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'vacancy-currencyid',
        'name': 'Vacancy[currencyId]',
        'container': '.field-vacancy-currencyid',
        'input': '#vacancy-currencyid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'vacancy-wage',
        'name': 'Vacancy[wage]',
        'container': '.field-vacancy-wage',
        'input': '#vacancy-wage',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
            
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('building-twotiled/vacancy-create', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
</script>