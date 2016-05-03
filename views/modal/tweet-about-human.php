<form>
<div class="input-group">
    <input id="human_nickname" placeholder="Никнейм человека" class="form-control" type="text">
    <span class="input-group-btn">
      <button id="check_nickname" type="button" class="btn btn-info btn-flat">Проверить</button>
    </span>
</div>
<div id="human_info"></div>
<input type="hidden" id="human_uid">
<p><select id="new_tweet_type">
  <option value="0">Нейтрально</option>
  <option value="1">Положительно</option>
  <option value="2">Отрицательно</option>
  <option value="3">Оскорбительно</option>
</select></p>
<p><textarea autofocus id="new_message_about_human" name="new_message"
      placeholder="Введите ваше сообщение" rows="5" class="socnet-textarea"></textarea>
      </p>
      <span id="symbols_count_about_human" class="pull-right" >140 символов осталось</span>
</form>
<script>
var check_nickname = function(callback){
      if ($('#human_nickname').val()) {
        get_json('userinfo',{'nick':$('#human_nickname').val()},function(userinfo){
          $('#human_uid').val(0);
          if (userinfo.result !== 'error') {
            userinfo = userinfo.result;
            $('#human_uid').val(userinfo.id)
            $('#human_info').html("<img src=\""+userinfo.photo+"\" alt=\"\"> "+userinfo.name);
            if (typeof callback==='function') callback(true);
          }
          else {
            $('#human_info').html("<span style='color:red'>Пользователь не найден!</span>");
            if (typeof callback==='function') callback(false);
          }
        },true);
      } else {
        if (typeof callback==='function') callback(false);
      }
    }
$(function(){


      $('#send_tweet_human').click(function(){
        check_nickname(function(s){
          if (s) {
            if($('#new_message_about_human').val())
              json_request('tweet',{'text':$('#new_message_about_human').val(),'uid':$('#human_uid').val(),'type':$('#new_tweet_type').val()})
          }
        })
      })

      $('#human_nickname').change(function(){
        var nick = $(this).val().replace("@","");
        if ($('#new_message_about_human').val().indexOf(nick) == -1) {
          $('#new_message_about_human').val("@"+nick+" "+$('#new_message_about_human').val());
        }
      })

$('#new_message_about_human').keydown(function(e){
      switch (e.keyCode){
        case 13:
        case 8:
        case 9:
        case 46:
        case 37:
        case 38:
        case 39:
        case 40:
          return true;
      }
      var c = ($(this).val().length>110)?'<span style="color:red">':'<span>'
        if ($(this).val().length < 140) $('#symbols_count_about_human').html(c + (139 - $(this).val().length) + ' символов осталось</span>');
        return ($(this).val().length < 140)
    }).keyup(function(){
      var c = ($(this).val().length>110)?'<span style="color:red">':'<span>'
      $('#symbols_count_about_human').html(c + (140 - $(this).val().length) + ' символов осталось</span>');
    })

    $('#check_nickname').click(check_nickname);

  })
</script>