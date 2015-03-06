<?php

use app\components\MyHtmlHelper;
use yii\helpers\Html;

?>

<h1>Ваши сделки</h1>

<h3>Предложения сделок:</h3>
<table class="table">
<? foreach ($user->getNotAcceptedDealingsList() as $dealing) {
    $items = json_decode($dealing->items,true);
    ?>
    <tr id="dealing_line<?=$dealing->id?>">
        <td><?= ($dealing->is_anonim) ? 'Неизвестный отправитель' : Html::a(Html::img($dealing->sender->photo,['style'=>'width:20px']).' '.$dealing->sender->name,'#',['onclick'=>'load_page("profile",{"uid":'.$dealing->from_uid.'})']) ?></td>
        <td><? if (intval($dealing->sum) !== 0) { ?><?= ($dealing->sum>0 ? 'Вы получите ' : 'Вы затратите ').  number_format(abs($dealing->sum),0,'',' ').' '.MyHtmlHelper::icon('coins')?><? } ?></td>
        <td>
            <? 
            if (is_array($items) && sizeof($items)) {
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

<script>
    function accept_dealing(id) {
        if (confirm("Вы действительно хотите заключить эту сделку?")) {
            json_request('accept-dealing',{'id':id},false);
            $('#dealing_line'+id).remove();
        }
    }
    
    function decline_dealing(id) {
        if (confirm("Вы действительно хотите отказаться от этой сделки?")) {
            json_request('decline-dealing',{'id':id},false);
            $('#dealing_line'+id).remove();
        }
    }
</script>