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
    <body class="skin-black sidebar-mini wysihtml5-supported">

        <?php $this->beginBody() ?>
        <div class="wrapper">
            <?= $content ?>
            <footer class="main-footer footer" <?php if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isInvited): ?>style="margin-left: 0"<?php endif ?> >
                <div class="pull-right hidden-xs">
                    <?=Yii::t('app', 'Icons by')?> <a href="http://www.fatcow.com/free-icons" target="_blank" >FatCow</a> & <a href="http://icons8.com/web-app/" target="_blank" >Icons8</a>
                </div>
                <?=Yii::t('app', 'Developed by')?> — <a href="http://lazzyteam.pw" target="_blank">Lazzy Team</a> 2011-<?= date('Y') ?>
            </footer>
        </div>
        <?php $this->endBody() ?>
        <script>
            $(function(){
                (adsbygoogle = window.adsbygoogle || []).push({});
            });
        </script>
    </body>
</html>
<?php $this->endPage() ?>
