<?php

use app\components\MyHtmlHelper,
    app\models\Dealing,
    app\models\Holding,
    app\models\factories\Factory,
    app\models\resources\proto\ResourceProto;
        

/* @var $id string */
/* @var $dealings Dealing[] */

?>
<table class="table table-normal" id="<?=$id?>">
    <thead>        
    <tr>
        <th style="min-width: 100px">Дата</th>
        <th>Отправитель</th>
        <th>Получатель</th>
        <th style="min-width: 80px">Деньги</th>
        <th style="min-width: 80px">Вещи</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($dealings as $dealing): 
        $items = json_decode($dealing->items,true);?>
        <tr>
            <td><i class="icon-time"></i> <span class="prettyDate" data-unixtime="<?=$dealing->time?>"><?=date('H:i:s d-m-Y',$dealing->time)?></span></td>
            <td><?= $dealing->sender ? $dealing->sender->getHtmlName() : 'unknown' ?></td>
            <td><?= $dealing->recipient ? $dealing->recipient->getHtmlName() : 'unknown' ?></td>
            <td><?=MyHtmlHelper::moneyFormat($dealing->sum,2)?></td>
            <td>
            <?php 
                if (is_array($items) && count($items)) {
                    echo "<ul>";
                foreach ($items as $item) {
                    echo "<li>";
                    switch ($item['type']) {
                        case 'stock':
                            $holding = Holding::findByPk($item['holding_id']);
                            echo $holding ? MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции")." компании «".$holding->getHtmlName()."»" : MyHtmlHelper::formateNumberword($item['count'], "каких-то акций", "какая-то акция", "какие-то акции");
                        break;
                        case 'factory':
                            $factory = Factory::findByPk($item['factory_id']);
                            echo $factory ? $factory->getHtmlName() : 'Какая-то фабрика';
                        break;
                        case 'resource':
                            $resProto = ResourceProto::findByPk($item['proto_id']);
                            echo $item['count'].' '.$resProto->icon;
                        break;
                    }
                    echo "</li>";
                }
                echo "</ul>";
                } ?>
            </td>
        </tr>        
    <?php endforeach ?>
    </tbody>
</table>