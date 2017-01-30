<?php

use app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $viewer app\models\User */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $shares app\models\economics\Resource[] */

$isOwner = $shareholder->getUtr() == $viewer->getUtr();

?>
<div class="box-group">
    <?php foreach ($shares as $share): ?>
    <div class="box box-solid box-info">
        <div class="box-header">
            <h4 class="box-title"><?= LinkCreator::companyLink($share->company)?></h4>
        </div>
        <div class="box-body">
            <?=MyHtmlHelper::formateNumberword($share->count, 's')?>
        </div>
        <?php if ($isOwner): ?>
        <div class="box-footer">
            <button class="btn btn-primary"><?=Yii::t('app', 'Control company')?></button>
        </div>
        <?php endif ?>
    </div>
    <?php endforeach ?>
</div>