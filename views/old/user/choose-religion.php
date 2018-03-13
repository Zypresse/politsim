<?php

/* @var $this \yii\web\View */
/* @var $religions app\models\Religion[] */
/* @var $user app\models\User */

?>
<div class="control-group">
    <label class="control-label" for="#new-religion-id"><?=Yii::t('app', 'New religion')?></label>
    <div class="controls">
        <select id="new-religion-id">
            <?php foreach ($religions as $religion): ?>
                <option <?php if ($user->religionId === $religion->id) : ?> selected="selected" <?php endif ?> value="<?= $religion->id ?>" ><?= $religion->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>