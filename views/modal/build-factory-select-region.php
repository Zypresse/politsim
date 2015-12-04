<?php

/* @var $holding app\models\Holding */
/* @var $regions app\models\Region[] */

?>

<div class="control-group" >
    <label class="control-label" for="#factory_new_region">Место строительства</label>
    <div class="controls">
        <select id="factory_new_region">
        <? foreach ($regions as $i => $region): ?>
            <? if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id): ?>
                <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
            <? endif ?>
            <option value="<?= $region->id ?>" <?= ((!$holding->region_id && $region->state_id === $holding->state_id && $region->isCapital()) || $region->id === $holding->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
        <? endforeach ?>
        </select>
    </div>
</div>
