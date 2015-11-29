<h5>Выборы лидера организации «<?=htmlspecialchars($org->name)?>»</h5>
<p><strong>Заявки на выборы подали:</strong></p>

<? foreach ($elect_requests as $elect_request) { ?>
<div class="row" style="margin-left:0">
<p><input style="margin-top: 20px; display:inline-block;" class="elect_vote_radio" type="radio" name="elect_vote" value="<?=$elect_request->id?>">
<a href="#" onclick="$('.modal-backdrop').hide();load_page('profile',{'uid':<?=$elect_request->candidat?>})"><?=htmlspecialchars($elect_request->user->name)?></a></p>
</div>
<? } ?>
<p><small>Поставьте галочку напротив выбранного вами кандидата и нажмите «Проголосовать»</small></p>
<script>
$(function(){
  $('.elect_vote_radio').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
  }).on('ifChecked', function(event){
  request_id = $(this).val();
  $('#elect_vote .btn-green').show();
});;
});
</script>