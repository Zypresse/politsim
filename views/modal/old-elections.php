<?php if ($results) { ?>
<table class="table"> 
<?php
foreach ($results as $result) {
?>
    <tr>
        <td><?= $result->leader ? 'Выборы лидера организации' : 'Выборы в организацию'?> «<?=$result->org->name?>»</td>
        <td><?=date('d-m-Y',$result->date)?></td>
        <td><button onclick="load_modal('elections-result',{'id':<?=$result->id?>},'old-elections','old-elections_body')" class="btn btn-sm btn-lightblue">Просмотр</button></td>
    </tr>
<?php
}
?>
</table>

<?php } else { ?>
<p>Выборов не проводилось</p>
<?php } ?>
