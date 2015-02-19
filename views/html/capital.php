<?php
use app\components\MyHtmlHelper;
?>
<h3>Капитал пользователя по имени <?=htmlspecialchars($user->name)?></h3>
<p>Наличные: <?=number_format($user->money,2,'.',' ')?> <?=MyHtmlHelper::icon('coins')?></p>
<h5>Последние совершённые сделки:</h5>
<table class="table">
<thead>
	<tr><th>От кого</th><th>Кому</th><th>Деньги</th><th>Вещи</th><th>Тип</th></tr>
</thead>
<tbody>
<? foreach ($dealings as $d) { ?>

<tr><td><? if (!$d->is_anonim || $d->from_uid == $viewer_id) { ?><a href="#" onclick="load_page('profile',{'uid':<?=$d->from_uid?>});"><img src="<?=$d->sender->photo?>" alt=""> <?=htmlspecialchars($d->sender->name)?></a><? } else { ?>Неизвестный отправитель<? } ?></td><td><a href="#" onclick="load_page('profile',{'uid':<?=$d->to_uid?>});"><img src="<?=$d->recipient->photo?>" alt=""> <?=htmlspecialchars($d->recipient->name)?></a></td><td><?=number_format($d->sum,2,'.',' ')?> <?=MyHtmlHelper::icon('coins')?></td><td></td><td><? if ($d->is_secret) { ?>Тайный<? } ?> <? if ($d->is_anonim) { ?>Анонимный<? } ?> <? if (!$d->is_secret && !$d->is_anonim) { ?>Обычный<? } ?></td></tr>

<? } ?>
</tbody>
</table>