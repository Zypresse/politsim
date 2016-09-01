<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\base\View */
/* @var $state app\models\State */
/* @var $user app\models\User */

?>
<section class="content-header">
    <h1>
        <?=Html::encode($state->name)?>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><?=Html::img($state->flag, ['style' => 'height: 8px; vertical-align: baseline;'])?> <?=Html::encode($state->name)?></li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <pre>
                <?php print_r($state) ?>
            </pre>
        </div>
    </div>
</section>