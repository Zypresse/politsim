<?php

use app\models\licenses\proto\LicenseProto,
    app\models\Holding,
    app\models\State;

/* @var $licenseProtos LicenseProto[] */
/* @var $holdng Holding */
/* @var $state State */

foreach ($licenseProtos as $lp) {
    if ($lp->isAllowed($state, $holding)) {
?>
        <option id="license_option<?= $lp->id ?>" value="<?= $lp->id ?>" data-text="<?= $lp->getText($state, $holding) ?>" ><?= $lp->name ?></option>      
<?php 
    }
}