<?php

use app\models\Region;

/* @var $regions Region[] */
/* @var $regionBase Region */

foreach ($regions as $i => $region) {
    $distance = $region->calcDist($regionBase);
    if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id) { ?>
        <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
    <?php } ?>
        <option data-distance="<?=$distance?>" value="<?= $region->id ?>" ><?= $region->name ?> (<?= number_format($distance, 2, '.', ' ') ?> км.)</option>
<?php
}