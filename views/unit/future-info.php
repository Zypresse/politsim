<?php

/* @var $this \yii\web\View */
/* @var $proto \app\models\economics\units\UnitProto */
/* @var $size integer */

?>
<?php if (count($proto->buildResourcesPacks)): ?>
    <h5>
    <?=Yii::t('app', 'Firm of type {0} of size {1} needs next resources:', [
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
        <?= Yii::t('app', 'Company needs next licenses to create:') ?>
    </h5>
    <ul>
        <?php foreach ($proto->buildLicenses as $licenseProto): ?>
            <li><?= $licenseProto->name ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
<?php if (count($proto->workPopsPacks)): ?>
    <h5>
        <?= Yii::t('app', 'Firm will needs around this workers counts:') ?>
    </h5>
    <ul>
        <?php foreach ($proto->workPopsPacks as $pack): ?>
            <li><?= $pack->popClass->name ?> — <?= $pack->count * $size ?></li>
        <?php endforeach ?>
    </ul>
<?php endif ?>