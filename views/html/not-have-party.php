<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper,
    app\models\Ideology;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<h4>Вы не состоите ни в одной партии</h4>
<?
if ($user->state) {
    ?>
<div class="btn-group">
<?
    if ($user->state->allow_register_parties) {
        ?>
  <button class="btn btn-sm dropdown-toggle btn-green" onclick="$('#create_party').modal();" >
    Создать партию
  </button>
        <?
    } else {
        ?>
        <p>В вашем государстве запрещено регистрировать партии</p>    
        <?
    }
    ?>

  <button class="btn btn-sm dropdown-toggle btn-primary" onclick="load_page('chart-parties',{'state_id':<?=$user->state_id?>})" >
    Рейтинг партий
  </button>
</div> 
  
<div style="display:none" class="modal" id="create_party" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Создание партии</h3>
  </div>
  
  <div id="create_party_body" class="modal-body">
    <form class="well form-horizontal">
      <div class="control-group">
	    <label class="control-label" for="#party_name">Название</label>
	    <div class="controls">
	      <input type="text" id="party_name" placeholder="Единая Россия">
	    </div>
	  </div>
    <div class="control-group">
      <label class="control-label" for="#party_name_short">Короткое название</label>
      <div class="controls">
        <input type="text" id="party_name_short" placeholder="ЕР">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="#party_ideology">Идеология</label>
      <div class="controls">
        <select id="party_ideology" >
        <? 
            $ideologies = Ideology::find()->all();
        foreach ($ideologies as $ideology) { ?>
          <option value="<?=$ideology->id?>"><?=htmlspecialchars($ideology->name)?></option>
        <? } ?>
        </select>
      </div>
    </div>
	  <div class="control-group">
	    <label class="control-label" for="#party_image">Ссылка на логотип<br><small>Используйте сервисы загрузки изображений, например <a href="https://imgur.com" target="_new">Imgur</a></small></label>
	    <div class="controls">
	  		<input type="text" id="party_image" placeholder="https://i.imgur.com/TNBKSPO.gif">
	  	</div>
	  </div>
	  <span class="help-block">Стоимость создания партии: <?=MyHtmlHelper::moneyFormat($user->state->register_parties_cost)?></span>
	  <!--<label class="checkbox">
	    <input type="checkbox"> Check me out
	  </label>
	  <button type="submit" class="btn btn-green">Submit</button>-->
	</form>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="create_party()">Создать</button>
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function create_party() {

        name = $('#party_name').val();
        name_short = $('#party_name_short').val();
        image = $('#party_image').val();
        ideology = $('#party_ideology').val();
        //$('.modal-backdrop').hide(); 
        json_request('create-party',{'name':name,'short_name':name_short,'image':image,'ideology':ideology},false);
        load_page('party-info',{},500);
        return true;
    }
</script>

  <?
} else {
    ?>
        <p>Вы не имеете гражданства и не можете зарегистрировать партию</p>    
    <?
}
?>