<?php

use app\components\MyHtmlHelper,
    app\models\Dealing,
    app\models\Holding,
    app\models\factories\Factory,
    app\models\resurses\proto\ResurseProto;
        

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
    <? foreach ($dealings as $dealing): 
        $items = json_decode($dealing->items,true);?>
        <tr>
            <td><i class="icon-time"></i> <span class="prettyDate" data-unixtime="<?=$dealing->time?>"><?=date('H:i:s d-m-Y',$dealing->time)?></span></td>
            <td><?= $dealing->sender->getHtmlName() ?></td>
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
                            $holding = Holding::findByPk($item['holding_id']);
                            echo MyHtmlHelper::formateNumberword($item['count'], "акций", "акция", "акции")." компании «".$holding->name."»";
                        break;
                        case 'factory':
                            $factory = Factory::findByPk($item['factory_id']);
                            echo $factory->getHtmlName();
                        break;
                        case 'resurse':
                            $resProto = ResurseProto::findByPk($item['proto_id']);
                            echo $item['count'].' '.MyHtmlHelper::icon($resProto->class_name);
                        break;
                    }
                    echo "</li>";
                }
                echo "</ul>";
                } ?>
            </td>
        </tr>        
    <? endforeach ?>
    </tbody>
</table>