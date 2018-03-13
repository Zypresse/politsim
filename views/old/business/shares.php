<?php

use app\components\LinkCreator,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $viewer app\models\User */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $shares app\models\economics\Resource[] */

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
        <div class="box-footer">
            <a href="#!company/control?id=<?=$share->company->id?>&utr=<?=$shareholder->getUtr()?>" class="btn btn-primary"><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Control company')?></a>
        </div>
    </div>
    <?php endforeach ?>
</div>