<h1>Аккаунты</h1>

<h3>Привязаны аккаунты в следющих соцсетях:</h3>
<ul>
<? foreach ($user->accounts as $acc): ?>
    <li><?=$acc->source?> (id <?=$acc->source_id?>)</li>
<? endforeach ?>
</ul>
    
<h3>Привязать ещё один аккаунт</h3>
<?= yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['site/auth'],
]); ?>
