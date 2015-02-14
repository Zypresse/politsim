<?
use app\components\MyHtmlHelper;
$own = ($viewer_id === $user->id);
?>
<div style="display:none" class="modal" id="tweet_about_human" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Написать пост о другом человеке</h3>
  </div>
  <div id="tweet_about_human_body" class="modal-body">
    Загрузка...
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <button class="btn btn-primary" id="send_tweet_human">Отправить</button>
  </div>
</div>
<? if (!$user->twitter_nickname && $own) { ?>
<div style="display:none" class="modal" id="set_twitter_nickname" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel2">У вас не установлен ник в соц. сетях!</h3>
  </div>
  <div id="set_twitter_nickname_body" class="modal-body">
    <p>Введите ваш никнейм, по нему о вас смогут писать другие пользователи</p>
    <p><input type="text" id="new_nickname" ></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    <button class="btn btn-primary" id="save_nickname" >Сохранить</button>
  </div>
</div>
<? } ?>
<div class="row" style="margin-left:0">
              <div class="span3">
              <p>
               <a  href="#" onclick="load_page('profile',{'uid':<?=$user->id?>});">
                <img style="vertical-align: top;" src="<?=$user->photo_big?>" alt=''>
              </a></p>
              <h4><a href="#" onclick="load_page('profile',{'uid':<?=$user->id?>});"><?=htmlspecialchars($user->name)?></a></h4>
              <? if ($user->twitter_nickname) { ?><h5 style="color:#333">@<?=$user->twitter_nickname?></h5><? } ?>
                <p><i class="icon-user"></i> <strong><?=number_format($user->getTwitterSubscribersCount(),0,'',' ')?></strong> подписчиков</p>
                <? if ($user->region) { ?><p><i class="icon-map-marker"></i> <?=htmlspecialchars($user->region->name)?> </p><? } ?>
              </div>
              <div class="span4" id="twitter_user_feed"><? if ($own) { ?>
              <h5>Выберите, о чём написать:</h5>
              <p>
                <button class="btn" onclick="$('#tweet_about_human_body').empty();$.get('/nodejs?a=tweet_about_human_modal',function(data){$('#tweet_about_human_body').html(data);$('#tweet_about_human').modal();})">О человеке</button>
                <button class="btn" disabled="disabled">О событии</button>
                <button class="btn" disabled="disabled">О погоде</button>
              </p>
              <h5>Новый пост:</h5>
                <textarea autofocus id="new_message" name="new_message"
                placeholder="Введите ваше сообщение" rows="5" class="socnet-textarea"></textarea>
                <h6 style="margin-top:0" class="pull-right"><span id="symbols_count">140 символов осталось</span></h6>
                <button class="btn btn-primary" type="submit" onclick="if($('#new_message').val())json_request('tweet',{'text':$('#new_message').val()})">Отправить</button>
              <br>
              <? } else { ?>
                <button class="btn btn-block btn-warning" onclick="load_page('twitter')">Вернуться свой профиль</button>
              <? } ?>
               <h4>Последние посты <small>(всего <?=$user->getTweetsCount()?>)</small></h4>
               <? foreach ($tweets as $i => $tweet) { ?>
                 <div class="tweet <? if ($i === sizeof($tweets)-1) { ?>last<? } ?>">
                 <strong><a href="#" onclick="load_page('twitter',{'uid':<?=$user->id?>})"><?=htmlspecialchars($user->name)?></a></strong> <span class="date"><?=date('d-M-Y H:i',$tweet->date)?></span>
                 <? if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->original?>})"><?=htmlspecialchars($tweet->originalUser->name)?></a></p><? } ?>
                 <p><?=MyHtmlHelper::parseTwitterLinks($tweet->text)?></p>
                 <p class="tweet-footer"><? if ($tweet->uid !== $viewer_id && !$own && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-small repost" data-id="<?=$tweet->id?>" title="Репост"><i class="icon-repeat"></i></a><? } else { ?><i class="icon-repeat"></i><? } ?> <?=MyHtmlHelper::formateNumberword($tweet->retweets,'репостов','репост','репоста')?> <? if ($own) { ?><button class="btn btn-danger btn-small delete delete_tweet" title="Удалить" data-id="<?=$tweet->id?>" >X</button><? } ?></p>
                 </div>
               <? } ?>
                  <button class="btn btn-block" id="update_user_feed" data-time="<?=$timeFeedGenerated?>" data-offset="3" data-uid="<?=$user->id?>" >Далее</button>
                </div>
                <div class="span4" id="twitter_feed">
                <h4>Популярные посты</h4>
               <? foreach ($feed as $i => $tweet) { ?>
                 <div class="tweet <? if ($i === sizeof($feed)-1) { ?>last<? } ?>">
                 <strong><a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->uid?>})"><?=htmlspecialchars($tweet->user->name)?></a></strong> <span class="date"><?=date('d-M-Y H:i',$tweet->date)?></span>
                 <? if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->original?>})"><?=htmlspecialchars($tweet->originalUser->name)?></a></p><? } ?>
                 <p><?=MyHtmlHelper::parseTwitterLinks($tweet->text)?></p>
                 <p class="tweet-footer"><? if ($tweet->uid !== $viewer_id && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-small repost" title="Репост" data-id="<?=$tweet->id?>"><i class="icon-repeat"></i></a><? } else { ?><i class="icon-repeat"></i><? } ?> <?=MyHtmlHelper::formateNumberword($tweet->retweets,'репостов','репост','репоста')?></p>
                 </div>
               <? } ?>
                <button class="btn btn-block" id="update_feed" data-time="<?=$timeFeedGenerated?>" data-offset="5" >Далее</button>
                </div></div>

                 <script>
                 function repost(id) {
                    if (confirm('Вы действительно хотите сделать репост?')) {
                      json_request('retweet',{'id':id});
                    }
                    return false;
                 }
 $(function () {
    <? if ($own) { ?>
      $('#set_twitter_nickname').modal();

      $('#save_nickname').click(function(){
        var nick = $('#new_nickname').val().toLowerCase();
        if (nick && !(/[^qwertyuiopasdfghjklzxcvbnm0123456789]/.test(nick)) && nick.length<20 && nick.length>3)
          json_request('set-twitter-nickname',{'nick':nick});
        else 
          alert('Ник должен содержать только латинские буквы и быть от 4 до 20 знаков в длину');
      })

    <? } ?>

    $('#update_feed').click(function(){
      $(this).attr("disabled","disabled");
      var offset = parseInt($(this).data('offset'));
      get_html('get-twitter-feed',{'time':$(this).data('time'),'offset':offset},function(rows){

          $('#twitter_feed .last').removeClass('last');
          $('#twitter_feed').append(rows);
          $('#twitter_feed').append($('#update_feed'));
          $('#update_feed').data('offset',offset+5).removeAttr("disabled");

      });
    })

    $('#update_user_feed').click(function(){
      $(this).attr("disabled","disabled");
      var offset = parseInt($(this).data('offset'));
      get_html('get-twitter-feed',{'time':$(this).data('time'),'offset':offset,'uid':$(this).data('uid')},function(rows){

          $('#twitter_user_feed .last').removeClass('last');
          $('#twitter_user_feed').append(rows);
          $('#twitter_user_feed').append($('#update_user_feed'));
          $('#update_user_feed').data('offset',offset+5).removeAttr("disabled");

      });
    })

    $( ".repost" ).on( "click", function() {
      if (confirm('Вы действительно хотите сделать репост?')) {
        json_request('retweet',{'id':$(this).data('id')});
      }
      return false;
    });
    $( ".delete_tweet" ).on( "click", function() {
      if (confirm('Вы действительно хотите удалить пост?')) {
        json_request('delete-tweet',{'id':$(this).data('id')});
      }
      return false;
    });

    $('#new_message').keydown(function(e){
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
        if ($(this).val().length < 140) $('#symbols_count').html(c + (139 - $(this).val().length) + ' символов осталось</span>');
        return ($(this).val().length < 140)
    }).keyup(function(){
      var c = ($(this).val().length>110)?'<span style="color:red">':'<span>'
      $('#symbols_count').html(c + (140 - $(this).val().length) + ' символов осталось</span>');
    })
  })
    </script>