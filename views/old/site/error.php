<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name . ' | Political Simulator';
?>

<div class="content" style="background-color: white">

    <h1><?= Html::encode($name) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?=Yii::t('app', 'The above error occurred while the Web server was processing your request.')?>
    </p>
    <p>
        <?=Yii::t('app', 'Please <a href="/contacts">contact</a> us if you think this is a server error. Thank you.')?>
    </p>

</div>
