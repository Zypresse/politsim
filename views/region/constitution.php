<?php

use yii\helpers\Html,
    app\components\LinkCreator,
    app\components\MyHtmlHelper,
    app\models\StateConstitution;

/* @var $this yii\base\View */
/* @var $region app\models\Region */
/* @var $constitution app\models\RegionConstitution */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Constitution of {0}', [Html::encode($region->name)])?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($region->state)?></li>
        <li><?=LinkCreator::regionLink($region)?></li>
        <li class="active"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Constitution')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered table-condensed">
                <thead></thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="text-center"><h4><?=Yii::t('app', 'Political properties')?></h4></td>
                    </tr>
                    <?php /*@TODO if ($constitution->leaderPost): ?>
                    <tr>
                        
                    </tr>
                    <?php endif */?>
                    
                    <tr>
                        <td colspan="2" class="text-center"><h4><?=Yii::t('app', 'Economical properties')?></h4></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>