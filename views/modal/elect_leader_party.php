<h5>Выборы лидера организации «<?=htmlspecialchars($org->name)?>»</h5>
<p><strong>Заявки на выборы подали следующие партии:</strong></p>

<? foreach ($elect_requests as $elect_request) { ?>
<div class="row" style="margin-left:0">
<p><input style="margin-top: 10px; display:inline-block;float: left; margin-right:20px" class="elect_vote_radio" type="radio" name="elect_vote" value="<?=$elect_request->id?>" data-child="0"> &nbsp; <a href="#" onclick="$('.modal-backdrop').hide();load_page('party-info',{'id':<?=$elect_request->party_id?>})"><strong><?=htmlspecialchars($elect_request->party->name)?></strong></a></p>
<p style="padding-left:30px"><small>Кандидат на должность «<?=htmlspecialchars($org->leader->name)?>»</small> — <a href="#" onclick="$('.modal-backdrop').hide();load_page('profile',{'uid':<?=$elect_request->candidat?>})"><?=htmlspecialchars($elect_request->user->name)?></a>
<!--<br>
<small>Кандидат на должность «{{ elect_request.child.org_info.post_name|escape }}»</small> — <a href="#" onclick="$('.modal-backdrop').hide();load_page('profile',{'uid':{{ elect_request.child.candidat }}})">{{ elect_request.child.candidat_name|escape }}</a></p>-->
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
  $('#elect_vote .btn-primary').show();
});;
});

</script>