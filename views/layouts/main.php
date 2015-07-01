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
    <body>

        <?php $this->beginBody() ?>
        <img src="/img/ajax-loader.gif" id="spinner" style="display:none" />
        
        <?= $content ?>
        
        <footer class='footer '>
            <div class="container">
                <div class="row">
                    <div class="span6">
                        <p>Разработка — <a href="http://lazzyteam.com" target="_blank">Lazzy Team</a> 2011-<?= date('Y') ?></p>

                    </div>
                    <div class="span6" style="text-align:right">
                        <p>Используются иконки <a href="http://www.fatcow.com/free-icons" target="_blank" >FatCow</a> и <a href="http://icons8.com/web-app/" target="_blank" >Icons8</a></p>
                    </div>
                </div>
            </div>
        </footer>
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
    </body>
</html>
<?php $this->endPage() ?>
