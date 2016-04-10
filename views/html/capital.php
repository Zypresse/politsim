<?php

use app\components\MyHtmlHelper;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Капитал пользователя по имени <?= htmlspecialchars($user->name) ?></h3>
            <p>Наличные: <?= number_format($user->money, 2, '.', ' ') ?> <?= MyHtmlHelper::icon('money') ?></p>
            <h4>Акции:</h4>
            <table class="table">
<?php if (count($user->stocks)) {
    foreach ($user->stocks as $stock) { ?>
                        <tr>
                            <td><a href="#" onclick="load_page('holding-info', {'id':<?= $stock->holding_id ?>})"><?= $stock->holding->name ?></a></td>
                            <td><?= MyHtmlHelper::formateNumberword($stock->count, "акций", "акция", "акции") ?> (<?= round($stock->getPercents(), 2) ?>%)</td>
                            <td>≈ <?= number_format($stock->getCost(), 0, '', ' ') ?> <?= MyHtmlHelper::icon('money') ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="2">Не владеет акциями</td></tr>
<?php } ?></table>
            <h4>Последние совершённые сделки:</h4>
            <table class="table">
                <thead>
                    <tr><th>Время</th><th>От кого</th><th>Кому</th><th>Деньги</th><th>Вещи</th><th>Тип</th></tr>
                </thead>
                <tbody>
<?php foreach ($dealings as $d) { ?>

                        <tr>
                            <td class="prettyDate" data-unixtime="<?= $d->time ?>"></td>
                            <td><?php if (!$d->is_anonim || $d->sender->id == $viewer_id) { ?><a href="#" onclick="load_page('profile', {'uid':<?= $d->sender->id ?>});"><img src="<?= $d->sender->photo ?>" alt=""> <?= htmlspecialchars($d->sender->name) ?></a><?php } else { ?>Неизвестный отправитель<?php } ?></td>
                            <td><a href="#" onclick="load_page('profile', {'uid':<?= $d->recipient->id ?>});"><img src="<?= $d->recipient->photo ?>" alt=""> <?= htmlspecialchars($d->recipient->name) ?></a></td><td><?= number_format($d->sum, 2, '.', ' ') ?> <?= MyHtmlHelper::icon('money') ?></td><td></td><td><?php if ($d->is_secret) { ?>Тайный<?php } ?> <?php if ($d->is_anonim) { ?>Анонимный<?php } ?> <?php if (!$d->is_secret && !$d->is_anonim) { ?>Обычный<?php } ?></td>
                        </tr>

<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>