<?php

use app\components\MyHtmlHelper;

/* @var $parties app\models\Party[] */

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Рейтинг партий</h1>
            <?php if ($state) { ?>
                <h3>Партии государства &laquo;<a href="#" onclick="load_page('state-info', {'id':<?= $state->id ?>})"><?= htmlspecialchars($state->name) ?></a>&raquo;</h3>
            <?php } ?>
            <table id="chart_parties" class="table table-striped">
                <tr><th style="width:60px;">Логотип</th><th style="width: 200px;">Название</th><th>Страна</th><th>Число участников</th><th>Характеристики</th></tr>
                <?php foreach ($parties as $party) { ?>
                    <tr>
                        <td>
                            <img src="<?= $party->image ?>" alt="<?= $party->name ?>" style="width:50px">
                        </td>
                        <td>
                            <?= $party->getHtmlName() ?>
                        </td>
                        <td>
                            <?= $party->state ? $party->state->getHtmlShortName() : '' ?>
                        </td>
                        <td><?= $party->getMembersCount() ?></td>
                        <td>
                            <span class="star"><?= $party->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                            <span class="heart"><?= $party->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                            <span class="chart_pie"><?= $party->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#chart_parties').tablePagination({'rowsPerPage': 10});
    });
</script>