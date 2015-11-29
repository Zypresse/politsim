<?php
/* @var $ideologies app\models\Ideology[] */
/* @var $user app\models\User */
?>
<div class="control-group">
    <label class="control-label" for="#new_ideology_id">Идеология</label>
    <div class="controls">
        <select id="new_ideology_id">
            <? foreach ($ideologies as $ideology): ?>
                <option <? if ($user->ideology_id === $ideology->id) : ?> selected="selected" <? endif ?> value="<?= $ideology->id ?>" ><?= $ideology->name ?></option>
            <? endforeach ?>
        </select>
    </div>
</div>