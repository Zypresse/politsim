<?php

use yii\authclient\widgets\AuthChoice,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
$this->title = 'Political Simulator';

?>

<header id="top" class="header">
    <div class="text-vertical-center container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12">
                <h1><?=MyHtmlHelper::icon('lg-icons/globe', 'vertical-align: baseline;')?> Political Simulator</h1>
                <h3>Мультиплеерный реалистичный симулятор геополитики и бизнеса.</h3>
                <p>Следите за обновлениями в социальных сетях: <a href="https://plus.google.com/110425397057830817568" rel="publisher" target="_blank">Google+</a>, <a href="https://vk.com/politsim" target="_blank">VK</a></p>
                <p>Так же рекомендуется к прочтению: <a href="http://politsim.tumblr.com">официальный блог разработки</a>, <a href="http://wiki.politsim.lazzyteam.pw">вики по игре</a>.</p>
                <h3>Вход через соц. сети:</h3>
                <?=AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth']
                ]);?>
            </div>
        </div>
    </div>
</header>