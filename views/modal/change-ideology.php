<?php
/* @var $ideologies app\models\Ideology[] */
/* @var $user app\models\User */
?>
<div class="control-group">
    <label class="control-label" for="#new_ideology_id">Идеология</label>
    <div class="controls">
        <select id="new_ideology_id">
            <?php foreach ($ideologies as $ideology): ?>
                <option <?php if ($user->ideology_id === $ideology->id) : ?> selected="selected" <?php endif ?> value="<?= $ideology->id ?>" ><?= $ideology->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>