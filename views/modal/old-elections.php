<?php if ($results) { ?>
<table class="table"> 
<?
foreach ($results as $result) {
?>
    <tr>
        <td><?= $result->leader ? 'Выборы лидера организации' : 'Выборы в организацию'?> «<?=$result->org->name?>»</td>
        <td><?=date('d-m-Y',$result->date)?></td>
        <td><button onclick="$.ajax(
            {
              url: '/api/modal/elections-result?id=<?=$result->id?>',
              beforeSend:function() {
                  $('#old-elections_body').html('<br><br><br>Загрузка...<br><br><br><br><br>');
              },
              success:function(d) {
                if (typeof(d) == 'object' && d.result == 'error') {
                    show_custom_error(d.error);
                } else {
                    $('#old-elections_body').html(d);
                    $('#old-elections').modal();
                }
              },
                error:show_error
            });" class="btn btn-sm btn-primary">Просмотр</button></td>
    </tr>
<?
}
?>
</table>

<? } else { ?>
<p>Выборов не проводилось</p>
<? } ?>
