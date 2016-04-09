<?php

use app\components\MyHtmlHelper;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Ваши сделки</h1>

            <h3>Предложения сделок:</h3>
            <?php
            $nadl = $user->getNotAcceptedDealingsList();
            if (count($nadl)) {
                ?>
                <table class="table">
                    <?php
                    foreach ($nadl as $dealing) {
                        if (!$dealing->sender || !$dealing->recipient) {
                            continue;
                        }
                        $items = json_decode($dealing->items, true);
                        ?>
                        <tr id="dealing_line<?= $dealing->id ?>">
                            <td><?= ($dealing->is_anonim || is_null($dealing->sender)) ? 'Неизвестный отправитель' : $dealing->sender->getHtmlName() ?></td>
                            <td><?php if (intval($dealing->sum) !== 0) { ?><?= ($dealing->sum > 0 ? 'Вы получите ' : 'Вы затратите ') . number_format(abs($dealing->sum), 0, '', ' ') . ' ' . MyHtmlHelper::icon('money') ?><?php } ?></td>
                            <td>
                                <?php
                                if (is_array($items) && count($items)) {
                                    echo "Вы получите: <ul>";
                                    foreach ($items as $item) {
                                        echo "<li>";
                                        switch ($item['type']) {
                                            case 'stock':
                                                $holding = app\models\Holding::findByPk($item['holding_id']);
                                                echo $holding ? MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции") . " компании «" . $holding->getHtmlName() . "»" : MyHtmlHelper::formateNumberword($item['count'], "каких-то акций", "какая-то акция", "какие-то акции");
                                                break;
                                            case 'factory':
                                                $factory = \app\models\factories\Factory::findByPk($item['factory_id']);
                                                echo $factory ? $factory->getHtmlName() : 'Какая-то фабрика';
                                                break;
                                            case 'resource':
                                                $resProto = \app\models\resources\proto\ResourceProto::findByPk($item['proto_id']);
                                                echo $item['count'] . ' ' . $resProto->icon;
                                                break;
                                        }
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-green" onclick="accept_dealing(<?= $dealing->id ?>)">Заключить</button>
                                <button class="btn btn-red" onclick="decline_dealing(<?= $dealing->id ?>)">Отказаться</button>
                            </td>
                        </tr>
    <?php } ?>
                </table>
                <script>
                    function accept_dealing(id) {
                        if (confirm("Вы действительно хотите заключить эту сделку?")) {
                            json_request('accept-dealing', {'id': id});
                            $('#dealing_line' + id).remove();
                        }
                    }

                    function decline_dealing(id) {
                        if (confirm("Вы действительно хотите отказаться от этой сделки?")) {
                            json_request('decline-dealing', {'id': id});
                            $('#dealing_line' + id).remove();
                        }
                    }
                </script>
            <?php
            } else {
                echo "<p>Нет предложений</p>";
            }
            ?>

            <h3>Последние заключённые сделки</h3>
<?php
$mdl = $user->getMyDealingsList();
?>
            <table class="table">
                <tr>
                    <th>Дата</th>
                    <th>Отправитель</th>
                    <th>Получатель</th>
                    <th>Деньги</th>
                    <th>Вещи</th>
                </tr>
                <?php
                foreach ($mdl as $dealing) {
                    if (!$dealing->sender || !$dealing->recipient) {
                        continue;
                    }
                    $items = json_decode($dealing->items, true);
                    ?>
                    <tr>
                        <td class="prettyDate" data-unixtime="<?= $dealing->time ?>"><?= date('d-m-Y H:i', $dealing->time) ?></td>
                        <td><?= (($dealing->is_anonim && !($dealing->sender->id === $user->id)) || is_null($dealing->sender)) ? 'Неизвестный отправитель' : $dealing->sender->getHtmlName() ?></td>
                        <td><?= is_null($dealing->recipient) ? 'Неизвестный получатель' : $dealing->recipient->getHtmlName() ?></td>
                        <td><?= $dealing->sum ?> <?= MyHtmlHelper::icon('money') ?></td>
                        <td>
                            <?php
                            if (is_array($items) && count($items)) {
                                echo "<ul>";
                                foreach ($items as $item) {
                                    echo "<li>";
                                    switch ($item['type']) {
                                        case 'stock':
                                            $holding = app\models\Holding::findByPk($item['holding_id']);
                                            echo $holding ? MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции") . " компании «" . $holding->getHtmlName() . "»" : MyHtmlHelper::formateNumberword($item['count'], "каких-то акций", "какая-то акция", "какие-то акции");
                                            break;
                                        case 'factory':
                                            $factory = \app\models\factories\Factory::findByPk($item['factory_id']);
                                            echo $factory ? $factory->getHtmlName() : 'Какая-то фабрика';
                                            break;
                                        case 'resource':
                                            $resProto = \app\models\resources\proto\ResourceProto::findByPk($item['proto_id']);
                                            echo $item['count'] . ' ' . $resProto->icon;
                                            break;
                                    }
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }
                            ?>
                        </td>
                    </tr>
<?php } ?>
            </table>
        </div>
    </div>
</div>