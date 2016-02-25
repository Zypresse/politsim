<?php 
    use yii\helpers\Html;
?>
<h3>Смена гимна</h3>
<form class="form-horizontal">
    <div class="control-group">	
        <label class="control-label" for="new_flag" >Ссылка на новый гимн на SoundCloud</label>
        <div class="controls">
            <input type="text" class="bill_field" id="new_anthem" name="new_anthem"  value="<?= $user->state->anthem ?>" >
        </div>
        <div class="help-block">
            Используйте ссылку на аудиозапись на <?=Html::a("SoundCloud.com","https://soundcloud.com/")?> формата 
            <em>https://soundcloud.com/{Имя пользователя}/{Название трека}</em>
        </div>
    </div>
</form>