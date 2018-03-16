<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
    </head>
    <body class="skin-black sidebar-mini">
	<?php $this->beginBody() ?>
        <div class="wrapper">
            <?= $this->render('_header') ?>
            <?= $this->render('_sidebar') ?>
            <div id="bigcontainer" class="content-wrapper">
                <?= $content ?>
            </div>
        </div>
	<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
