<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->registerCss("
    html,body {
        width: 100%;
        height: 100%;
        margin: 0;
        color: white;
    }
    h3 {
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .text-vertical-center {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
    }

    .text-vertical-center h1 {
        margin: 0;
        padding: 0;
        font-size: 4.5em;
        font-weight: 700;
        line-height: 1.1;
    }

    #top.header {
        display: table;
        position: relative;
        width: 100%;
        height: 100%;
        background: url(/img/bg.jpg) no-repeat center center scroll;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
    }
    
    .col-md-8 {
        background-color: rgba(0,0,0,0.8);
        border-radius: 10px;
        padding: 32px 0;
    }
");

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
            <?= $content ?>
        <footer class="main-footer footer" style="margin-left: 0" >
            <div class="pull-right hidden-xs">
                <?=Yii::t('app', 'Icons by')?> <a href="http://www.fatcow.com/free-icons" target="_blank" >FatCow</a> & <a href="http://icons8.com/web-app/" target="_blank" >Icons8</a>
            </div>
            <?=Yii::t('app', 'Developed by')?> <a href="http://lazzyteam.pw" target="_blank">Lazzy Team</a> 2011-<?= date('Y') ?>
        </footer>
        <?php $this->endBody() ?>
        <script>
            $(function(){
                (adsbygoogle = window.adsbygoogle || []).push({});
            });
        </script>
    </body>
</html>
<?php $this->endPage() ?>
