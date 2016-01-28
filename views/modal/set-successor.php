<?php

/* @var $users app\models\User[] */

?>
<h3>Кандидаты:</h3>
<table id="chart_peoples" class="table table-striped">
    <tr>
        <th style="width: 200px;">Имя</th>
        <th>Партия</th>
        <th>Характеристики</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($users as $player): ?>
        <tr>
            <td>
                <?=$player->getHtmlName()?>
            </td>
            <td>
                <?php if ($player->party): ?>
                <?=$player->party->getHtmlName()?>
                <?php else:
                echo ($player->sex == 1) ? 'Беспартийная' : 'Беспартийный'; 
                endif ?>
            </td>
            <td>
                <span class="star"><?= $player->star ?> <img src="/img/star.png" alt="Известность" title="Известность" style=""></span>
                <span class="heart"><?= $player->heart ?> <img src="/img/heart.png" alt="Доверие" title="Доверие" style=""></span>
                <span class="chart_pie"><?= $player->chart_pie ?> <img src="/img/chart_pie.png" alt="Успешность" title="Успешность" style=""></span>
            </td>
            <td><button class="btn btn-primary" onclick="set_successor(<?= $player->id ?>, '<?= htmlspecialchars($player->name) ?>', '<?= htmlspecialchars($post->name) ?>')" >Назначить</button></td>
    </tr>
    <?php endforeach ?>
</table>
<script>
    $(function () {
        $('#chart_peoples').tablePagination({'rowsPerPage': 10});
    });
</script>