<?php

use app\helpers\Html;

/* @var $this \yii\web\View */
/* @var $inline boolean */

?>
<div class="social-auth-links  <?= $inline ? '' : 'text-center' ?>">
    <?= Html::a('<i class="fa fa-vk"></i> Войти через VK', ['/auth/auth', 'authclient' => 'vkontakte'], ['class' => 'btn btn-social btn-vk btn-flat ' . ($inline ? '' : 'btn-block')]) ?>
    <?= Html::a('<i class="fa fa-facebook"></i> Войти через Facebook', "https://deletefacebook.com/" /* ['/auth/auth', 'authclient' => 'facebook'] */, ['class' => 'btn btn-social btn-facebook btn-flat ' . ($inline ? '' : 'btn-block')]) ?>
    <?= Html::a('<i class="fa fa-google-plus"></i> Войти через Google+', ['/auth/auth', 'authclient' => 'google'], ['class' => 'btn btn-social btn-google btn-flat ' . ($inline ? '' : 'btn-block')]) ?>
</div>
