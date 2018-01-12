<?php

/* @var $this \yii\web\View */
/* @var $proto \app\models\economics\units\BuildingTwotiledProto */
/* @var $size integer */

?>
<?php if (count($proto->buildResourcesPacks)): ?>
    <h5>
    <?=Yii::t('app', 'Building of type {0} of size {1} needs next resources to build:', [
        $proto->name,
        $size,
    ])?>
    </h5>
    <ul>
        <?php foreach ($proto->buildResourcesPacks as $pack): ?>
            <li><?= $pack->proto->name ?> — <?= $pack->count * $size ?> <?= $pack->proto->icon ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
<?php if (count($proto->buildLicenses)): ?>
    <h5>
        <?= Yii::t('app', 'Company needs next licenses to build:') ?>
    </h5>
    <ul>
        <?php foreach ($proto->buildLicenses as $licenseProto): ?>
            <li><?= $licenseProto->name ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
<?php if (count($proto->workPopsPacks)): ?>
    <h5>
        <?= Yii::t('app', 'Building will needs around this workers counts:') ?>
    </h5>
    <ul>
        <?php foreach ($proto->workPopsPacks as $pack): ?>
            <li><?= $pack->popClass->name ?> — <?= $pack->count * $size ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>