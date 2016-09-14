<?php

use yii\helpers\Html,
    yii\jui\AutoComplete;

/* @var $this \yii\web\View */
/* @var $model \app\models\PartyPost */
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
<?=Html::hiddenInput('set-party-post-user-id', $model->successorId, ['id' => 'set-party-post-user-id'])?>
<?=AutoComplete::widget([
    'name' => 'set-party-post-user-id-input',
    'options' => [
        'id' => 'set-party-post-user-id-input',
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
    <?=Yii::t('app', 'Current user')?>: 
    <?=Html::img($model->user ? Html::encode($model->user->avatar) : '/img/profile.png', ['style' => 'height: 16px; vertical-align: top;'])?> 
    <span><?=$model->user ? '<a href="#!profile&id='.$model->user->id.'">'.Html::encode($model->user->name).'</a>' : '<span class="text-red">'.Yii::t('app', 'Not set').'</span>'?></span>
</div>
<div>
    <?=Yii::t('app', 'New user')?>: 
    <?=Html::img($model->user ? Html::encode($model->user->avatar) : '/img/profile.png', ['id' => 'set-party-post-user-avatar', 'style' => 'height: 16px; vertical-align: top;'])?> 
    <span id="set-party-post-user-name"><?=$model->user ? '<a href="#!profile&id='.$model->user->id.'">'.Html::encode($model->user->name).'</a>' : '<span class="text-red">'.Yii::t('app', 'Not set').'</span>'?></span>
</div>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>
        
    $('#set-party-post-user-id-input').autocomplete({
        select: function( event, ui ) {
            $( "#set-party-post-user-id-input" ).val( ui.item.label );
            $( "#set-party-post-user-id" ).val( ui.item.value );
            $( "#set-party-post-user-avatar" ).attr('src', ui.item.avatar);
            $( "#set-party-post-user-name" ).html('<a href="#!profile&id='+ui.item.value+'">'+ui.item.label+'</a>');

            return false;
        }     
    }).focus();
</script>