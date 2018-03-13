<?php

/* @var $this \yii\web\View */
/* @var $ideologies app\models\Ideology[] */
/* @var $user app\models\User */

?>
<div class="control-group">
    <label class="control-label" for="#new-ideology-id"><?=Yii::t('app', 'New ideology')?></label>
    <div class="controls">
        <select id="new-ideology-id">
            <?php foreach ($ideologies as $ideology): ?>
                <option <?php if ($user->ideologyId === $ideology->id) : ?> selected="selected" <?php endif ?> value="<?= $ideology->id ?>" ><?= $ideology->name ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>