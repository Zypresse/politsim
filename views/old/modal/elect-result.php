<?php

use app\components\MyHtmlHelper;

$data = json_decode($result->data);
?>
<h3><?= $result->leader ? 'Выборы лидера организации' : 'Выборы в организацию'?> «<?=$result->org->name?>»</h3>
<p>Дата проведения: <?=date('d-m-Y',$result->date)?></p>
<p>Явка: <?=MyHtmlHelper::formateNumberword($data->yavka_people,'h')?> (<?=$data->yavka_percents?>%)</p>
<p>Результаты:</p>
<ul>
    <?php foreach ($data->results as $res) { 
        if ($result->leader) {?>
    <li><a href="#" onclick="load_page('profile',{'uid':<?=$res->uid?>})"><?=$res->name?></a> (<?php if ($res->party_id) { ?><a href="#" onclick="load_page('party-info',{'id':<?=$res->party_id?>})"><?php } ?><?=isset($res->party_short_name) ? $res->party_short_name : $res->party_name?><?php if ($res->party_id) { ?></a><?php } ?>) — <?=MyHtmlHelper::formateNumberword($res->votes_population,'голосов','голос','голоса')?> (<?=$res->votes_percents?>%)</li>
        <?php } else { ?>
    <li><a href="#" onclick="load_page('party-info',{'id':<?=$res->id?>})"><?=$res->name?></a> — <?=MyHtmlHelper::formateNumberword($res->votes_population,'голосов','голос','голоса')?> (<?=$res->votes_percents?>%)</li>
    <?php }} ?>
</ul>
