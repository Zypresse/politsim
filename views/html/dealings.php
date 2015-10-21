<?php

use app\components\MyHtmlHelper;
use yii\helpers\Html;

?>

<h1>Ваши сделки</h1>

<h3>Предложения сделок:</h3>
<? 
$nadl = $user->getNotAcceptedDealingsList();
if (count($nadl)) { ?>
<table class="table">
<? foreach ($nadl as $dealing) {
    $items = json_decode($dealing->items,true);
    ?>
    <tr id="dealing_line<?=$dealing->id?>">
        <td><?= ($dealing->is_anonim) ? 'Неизвестный отправитель' : $dealing->sender->getHtmlName() ?></td>
        <td><? if (intval($dealing->sum) !== 0) { ?><?= ($dealing->sum>0 ? 'Вы получите ' : 'Вы затратите ').  number_format(abs($dealing->sum),0,'',' ').' '.MyHtmlHelper::icon('money')?><? } ?></td>
        <td>
            <? 
            if (is_array($items) && count($items)) {
                echo "Вы получите: <ul>";
            foreach ($items as $item) {
                echo "<li>";
                switch ($item['type']) {
                    case 'stock':
                        $holding = app\models\Holding::findByPk($item['holding_id']);
                        echo MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции")." компании «".$holding->name."»";
                    break;
                }
                echo "</li>";
            }
            echo "</ul>";
            } ?>
        </td>
        <td>
            <button class="btn btn-success" onclick="accept_dealing(<?=$dealing->id?>)">Заключить</button>
            <button class="btn btn-danger" onclick="decline_dealing(<?=$dealing->id?>)">Отказаться</button>
        </td>
    </tr>
<? } ?>
</table>
<script>
    function accept_dealing(id) {
        if (confirm("Вы действительно хотите заключить эту сделку?")) {
            json_request('accept-dealing',{'id':id});
            $('#dealing_line'+id).remove();
        }
    }
    
    function decline_dealing(id) {
        if (confirm("Вы действительно хотите отказаться от этой сделки?")) {
            json_request('decline-dealing',{'id':id});
            $('#dealing_line'+id).remove();
        }
    }
</script>
<? } else {
 echo "<p>Нет предложений</p>";
}?>

<h3>Последние заключённые сделки</h3>
<?
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
    <?    foreach ($mdl as $dealing) {
        $items = json_decode($dealing->items,true);
        ?>
    <tr>
        <td class="prettyDate" data-unixtime="<?=$dealing->time?>"><?=date('d-m-Y H:i',$dealing->time)?></td>
        <td><?= ($dealing->is_anonim && !($dealing->sender->id === $user->id)) ? 'Неизвестный отправитель' : $dealing->sender->getHtmlName() ?></td>
        <td><?= $dealing->recipient->getHtmlName() ?></td>
        <td><?=$dealing->sum?> <?=MyHtmlHelper::icon('money')?></td>
        <td>
        <? 
            if (is_array($items) && count($items)) {
                echo "<ul>";
            foreach ($items as $item) {
                echo "<li>";
                switch ($item['type']) {
                    case 'stock':
                        $holding = app\models\Holding::findByPk($item['holding_id']);
                        echo MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции")." компании «".$holding->name."»";
                    break;
                }
                echo "</li>";
            }
            echo "</ul>";
            } ?>
        </td>
    </tr>
    <? } ?>
</table>