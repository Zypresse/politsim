<?php

/* @var $viewer app\models\Holding */
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
            <option value="<?= $region->id ?>" <?= ((!$viewer->region_id && $region->state_id === $viewer->state_id && $region->isCapital()) || $region->id === $viewer->region_id) ? "selected='selected'" : '' ?>><?= $region->name ?></option>
        <? endforeach ?>
        </select>
    </div>
</div>
