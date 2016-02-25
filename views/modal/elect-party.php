<?php
use app\components\MyHtmlHelper;
?>
<h5>Выборы в организацию «<?=htmlspecialchars($org->name)?>»</h5>
<p><strong>Заявки на выборы подали следующие партии:</strong></p>

<?php foreach ($elect_requests as $elect_request) { ?>
<div class="row" style="margin-left:0">

<input style="display:inline-block;" class="elect_vote_radio" type="radio" name="elect_vote" value="<?=$elect_request->id?>">

<p style="display:inline-block;line-height: 28px;"><strong><?=MyHtmlHelper::a($elect_request->party->name, "load_page('party-info',{'id':{$elect_request->party->id}})")?></strong></p>
</div>
<?php } ?>
<p><small>Поставьте галочку напротив выбранной вами партии и нажмите «Проголосовать»</small></p>
<script>
$(function(){
  $('.elect_vote_radio').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
  }).on('ifChecked', function(event){
  request_id = $(this).val();
  console.log(request_id);
  $('#vote_button').show();
});;
});
</script>