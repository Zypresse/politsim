<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $agency app\models\Agency */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($agency->name)?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::stateLink($agency->state)?></li>
        <li class="active"><?=Html::encode($agency->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header">                    
            <h1>
                <?=Html::encode($agency->name)?>
                 <small>(<?=Html::encode($agency->nameShort)?>)</small>
            </h1>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered no-margin">
                        <tbody>
                            <tr>
                                <td><strong><i class="fa fa-flag"></i> <?=Yii::t('app', 'State')?></strong></td>
                                <td><?=LinkCreator::stateLink($agency->state)?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="btn-group text-center">
                                        <a href="#!state/constitution&agencyId=<?=$agency->id?>" class="btn btn-info"><i class="fa fa-list-alt"></i> <?=Yii::t('app', 'Look constitution')?></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>