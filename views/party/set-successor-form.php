<?php

use yii\helpers\Html,
    yii\jui\AutoComplete;

/* @var $this \yii\web\View */
/* @var $model \app\models\politics\PartyPost */
/* @var $user \app\models\User */
/* @var $candidats \app\models\User[] */

$source = [];
foreach ($candidats as $candidat) {
    $source[] = [
        'value' => $candidat->id,
        'label' => Html::encode($candidat->name),
        'avatar' => Html::encode($candidat->avatar)
    ];
}

?>
<div class="form-group">
<?=Html::hiddenInput('successor-user-id', $model->successorId, ['id' => 'successor-user-id'])?>
<?=AutoComplete::widget([
    'name' => 'successor-user-id-input',
    'options' => [
        'id' => 'successor-user-id-input',
        'class' => 'form-control',
        'autofocus' => 'autofocus',
        'placeholder' => Yii::t('app', 'Type user name'),
    ],
    'clientOptions' => [
        'autoFocus' => true,
        'source' => $source,
    ],
])?>
</div>
<div>
    <?=Yii::t('app', 'Current successor')?>: 
    <?=Html::img($model->successor ? Html::encode($model->successor->avatar) : '/img/profile.png', ['style' => 'height: 16px; vertical-align: top;'])?> 
    <span><?=$model->successor ? '<a href="#!profile&id='.$model->successor->id.'">'.Html::encode($model->successor->name).'</a>' : '<span class="text-red">'.Yii::t('app', 'Not set').'</span>'?></span>
</div>
<div>
    <?=Yii::t('app', 'New successor')?>: 
    <?=Html::img($model->successor ? Html::encode($model->successor->avatar) : '/img/profile.png', ['id' => 'successor-user-avatar', 'style' => 'height: 16px; vertical-align: top;'])?> 
    <span id="successor-user-name"><?=$model->successor ? '<a href="#!profile&id='.$model->successor->id.'">'.Html::encode($model->successor->name).'</a>' : '<span class="text-red">'.Yii::t('app', 'Not set').'</span>'?></span>
</div>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>
        
    $('#successor-user-id-input').autocomplete({
        select: function( event, ui ) {
            $( "#successor-user-id-input" ).val( ui.item.label );
            $( "#successor-user-id" ).val( ui.item.value );
            $( "#successor-user-avatar" ).attr('src', ui.item.avatar);
            $( "#successor-user-name" ).html('<a href="#!profile&id='+ui.item.value+'">'+ui.item.label+'</a>');

            return false;
        }     
    }).focus();
</script>