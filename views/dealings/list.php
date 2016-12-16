<?php

use app\components\LinkCreator;

/* @var $this yii\base\View */
/* @var $initiated app\models\economics\Dealing[] */
/* @var $received app\models\economics\Dealing[] */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Yii::t('app', 'Dealings')?>
    </h1>
    <ol class="breadcrumb">
        <li><?=LinkCreator::userLink($user)?></li>
        <li class="active"><i class="fa fa-briefcase"></i> <?=Yii::t('app', 'Dealings')?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <pre>
                <?php var_dump($initiated) ?>
                <?php var_dump($received) ?>
            </pre>
        </div>
    </div>
</section>
            