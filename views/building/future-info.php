<?php

/* @var $this \yii\web\View */
/* @var $proto \app\models\economics\units\BuildingProto */
/* @var $size integer */

?>
<h5>
<?=Yii::t('app', 'Building of type {0} of size {1} needs next resources to build:', [
    $proto->name,
    $size,
])?>
</h5>
<ul>
<?php foreach ($proto->buildResourcesPacks as $pack): ?>
    <li><?=$pack->proto->name?> — <?=$pack->count*$size?> <?=$pack->proto->icon?></li>
<?php endforeach ?>
</ul>