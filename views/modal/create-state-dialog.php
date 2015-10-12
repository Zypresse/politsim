<?php

  $short_name = '';
  $p = explode(' ',mb_strtoupper($region->name));
  foreach ($p as $pp) {
      $short_name .= mb_substr($pp,0,2);
  }

?>
<form class="well form-horizontal" id="create_state_form">
    
     <div class="control-group">
        <label class="control-label" for="#create_state_form_capital">Столица:</label>
        <div class="controls">
          <input type="hidden" id="create_state_form_capital" value="<?=$region->id?>">&laquo;<?=htmlspecialchars($region->city)?>&raquo;
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="#goverment_form">Форма правления:</label>
        <div class="controls">
          <select id="goverment_form">
    <? foreach ($forms as $form) { ?>
        <option value="<?=$form['id']?>"  ><?=$form['name']?></option>
    <? } ?>
    </select>
        </div>
      </div>
    <div class="control-group">
        <label class="control-label" for="#create_state_form_name">Название:</label>
        <div class="controls">
          <input type="text" id="create_state_form_name" value="<?=htmlspecialchars($region->name)?>">
        </div>
      </div>
    <div class="control-group">
        <label class="control-label" for="#create_state_form_short_name">Короткое название:</label>
        <div class="controls">
          <input type="text" id="create_state_form_short_name" value="<?=htmlspecialchars($short_name)?>">
        </div>
      </div> 
      <div class="control-group">
        <label class="control-label" for="#create_state_form_color">Цвет государства:</label>
        <div class="controls">
          <input type='text' id="create_state_form_color" value="#eeeeee">
        </div>
      </div>
    <div class="control-group">
        <label class="control-label" for="#create_state_form_flag">Ссылка на флаг<br><small>Используйте сервисы загрузки изображений, например <a href="https://imgur.com" target="_new">Imgur</a></small></label>
        <div class="controls">
            <input type="text" id="create_state_form_flag" placeholder="https://i.imgur.com/TNBKSPO.gif">
        </div>
      </div>
    </form>
<script type="text/javascript" src="/js/spectrum.js"></script>
    <script>
    $(function(){
$('#create_state_submit').click(function(){
	json_request("create-state",{'name':$('#create_state_form_name').val(),'short_name':$('#create_state_form_short_name').val(),'goverment_form':$('#goverment_form').val(),'capital':$('#create_state_form_capital').val(),'color':$('#create_state_form_color').val(),'flag':$('#create_state_form_flag').val()},true);
	load_page("state-info",{'show_create_party':1},500);
});


$("#create_state_form_color").spectrum({
	preferredFormat: "hex",
    showInput: true,
    showPalette: true,
    clickoutFiresChange: true,
    //hideAfterPaletteSelect:true,
    showButtons: false,

    palette: [
        [/*"rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(153, 153, 153)","rgb(183, 183, 183)",*/
        "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(239, 239, 239)", "rgb(243, 243, 243)", "rgb(255, 255, 255)"],
        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
        "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
        "rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
        "rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",
        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
    ]
});


})
    </script>