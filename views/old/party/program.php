<?php

use app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $party app\models\politics\Party */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Political program')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::partyLink($party)?></li>
        <li class="active"><?=Yii::t('app', 'Political program')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <?=$party->textHTML?>
        </div>
    </div>
</section>