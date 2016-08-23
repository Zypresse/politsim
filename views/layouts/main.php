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
        <img src="/img/ajax-loader.gif" id="spinner" style="display:none" />
        <div class="wrapper">
            <?= $content ?>
            <footer class="main-footer footer" <?php if (Yii::$app->user->isGuest || !Yii::$app->user->identity->isInvited): ?>style="margin-left: 0"<?php endif ?> >
                <div class="pull-right hidden-xs">
                    Используются иконки <a href="http://www.fatcow.com/free-icons" target="_blank" >FatCow</a> и <a href="http://icons8.com/web-app/" target="_blank" >Icons8</a>
                </div>
                Разработка — <a href="http://lazzyteam.com" target="_blank">Lazzy Team</a> 2011-<?= date('Y') ?>
            </footer>
        </div>
        <?php
            if (!Yii::$app->user->isGuest && Yii::$app->user->identity):
        ?>
        <script type="text/javascript"> 
            var viewer_id = <?= Yii::$app->user->identity->id ?>;
            var auth_key = "<?= Yii::$app->user->identity->authKey ?>";
        </script>
        <?php
            endif;
        ?>
<?php $this->endBody() ?>
        <script>
        $(function(){
            (adsbygoogle = window.adsbygoogle || []).push({});
        })
        </script>
    </body>
</html>
<?php $this->endPage() ?>
