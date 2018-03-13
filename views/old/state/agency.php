<?php

use yii\helpers\Html,
    app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $agency app\models\politics\Agency */
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>