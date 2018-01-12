<?php

use yii\helpers\Url,
    yii\bootstrap\ActiveForm,
    franciscomaya\sceditor\SCEditor;

/* @var $this \yii\web\View */
/* @var $model \app\models\politics\Party */
/* @var $user \app\models\User */

$form = new ActiveForm();

?>

<?php $form->begin([
    'options' => [
        'id' => 'edit-text-form',
    ],
    'action' => Url::to(['party/edit-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['party/edit-form'])
]) ?>

<?=$form->field($model, 'id', [
    'labelOptions' => ['class' => 'hide']
])->hiddenInput()?>


<?=$form->field($model, 'text')->widget(SCEditor::className(), [
        'options' => ['rows' => 20],
        'clientOptions' => [
            'plugins' => 'bbcode',
            'toolbarExclude' => 'ltr,font,youtube,emoticon',
        ]
    ])?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#edit-text-form');
    
    $form.yiiActiveForm('add', {
        'id': 'party-text',
        'name': 'Party[text]',
        'container': '.field-party-text',
        'input': '#party-text',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('party/edit', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
</script>