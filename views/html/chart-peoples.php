<?php

use app\components\MyHtmlHelper;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Рейтинг игроков</h1>
            <p>Вы на <strong><?= $place ?></strong> месте!</p>
            <table id="chart_peoples" class="table table-striped">
                <tr><th style="min-width: 250px;">Имя</th><th>Страна</th><th>Партия</th><th>Характеристики</th></tr>
<?php foreach ($users as $player) { ?>
                    <tr>
                        <td>
                            <a href="#" onclick="load_page('profile', {'uid':<?= $player->id ?>})"><img src="<?= $player->photo ?>" alt="" style="width:32px;height:32px;"></a>
                            &nbsp;
                            <a href="#" onclick="load_page('profile', {'uid':<?= $player->id ?>})"><?= htmlspecialchars($player->name) ?></a></td>
                        <td><?php if ($player->state) { ?><?= $player->state->getHtmlShortName() ?><?php } else { ?><?php if ($player->sex === 1) { ?>Гражданка<?php } else { ?>Гражданин<?php } ?> мира<?php } ?></td>
                        <td><?php if ($player->party) { ?><?= $player->party->getHtmlName() ?><?php } else { ?><?php if ($player->sex === 1) { ?>Беспартийная<?php } else { ?>Беспартийный<?php } ?><?php } ?></td>
                        <td>
                            <span class="star"><?= $player->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                            <span class="heart"><?= $player->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                            <span class="chart_pie"><?= $player->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                        </td>
                    </tr>
<?php } ?>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#chart_peoples').tablePagination({'rowsPerPage': 10});
    });
</script>