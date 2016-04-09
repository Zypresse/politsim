<?php

/* @var $holdinga app\models\Holding[] */

use app\components\MyHtmlHelper;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Рейтинг компаний</h1>
            <table id="chart_states" class="table table-striped">
                <tr><th style="min-width: 250px;">Название</th><th>Страна</th><th>Капитализация</th></tr>
                        <?php foreach ($holdings as $holding) { ?>
                    <tr>
                        <td><a href="#" onclick="load_page('holding-info', {'id':<?= $holding->id ?>})"><?= htmlspecialchars($holding->name) ?></a></td>
                        <td><?= $holding->state ? $holding->state->getHtmlShortName() : '' ?></td>
                        <td><?= MyHtmlHelper::aboutNumber($holding->capital) ?> <?= MyHtmlHelper::icon('money') ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#chart_states').tablePagination({'rowsPerPage': 10});
    });
</script>