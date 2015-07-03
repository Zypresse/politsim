<h5>Выборы в организацию «<?=htmlspecialchars($org->name)?>»</h5>
<p><strong>Заявки на выборы подали следующие партии:</strong></p>

<? foreach ($elect_requests as $elect_request) { ?>
<div class="row" style="margin-left:0">

<input style="display:inline-block;" class="elect_vote_radio" type="radio" name="elect_vote" value="<?=$elect_request->id?>">

<p style="display:inline-block;line-height: 28px;"><a href="#" onclick="$('.modal-backdrop').hide();load_page('party-info',{'id':<?=$elect_request->party_id?>})"><strong><?=htmlspecialchars($elect_request->party->name)?></strong></a></p>
</div>
<? } ?>
<p><small>Поставьте галочку напротив выбранной вами партии и нажмите «Проголосовать»</small></p>
<script>
$(function(){
  $('.elect_vote_radio').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
  }).on('ifChecked', function(event){
  request = $(this).val();
  console.log(request);
  $('#elect_vote .btn-primary').show();
});;
});
</script>