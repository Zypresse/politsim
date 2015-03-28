<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper;
?>

<div class="row" style="margin-left:0">
              <div class="span3">
                  <h4>Популярные теги:</h4>
              </div>
              <div class="span4" id="twitter_user_feed">
                <button class="btn btn-block btn-warning" onclick="load_page('twitter')">Вернуться свой профиль</button>
               <h4>Последние посты по тегу #<?=$tag?></h4>
               <? foreach ($tweets as $i => $tweet) { ?>
                 <div class="tweet <? if ($i === count($tweets)-1) { ?>last<? } ?>">
                 <strong><a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->uid?>})"><?=htmlspecialchars($tweet->user->name)?></a></strong> <span class="date prettyDate" data-unixtime="<?=$tweet->date?>"><?=date('d-M-Y H:i',$tweet->date)?></span>
                 <? if ($tweet->originalUser) { ?><p class="date">Репост от <a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->original?>})"><?=htmlspecialchars($tweet->originalUser->name)?></a></p><? } ?>
                 <p><?=MyHtmlHelper::parseTwitterLinks($tweet->text)?></p>
                 <p class="tweet-footer"><? if ($tweet->uid !== $viewer_id && $tweet->original !== $viewer_id) { ?><a href="#" class="btn btn-small repost" data-id="<?=$tweet->id?>" title="Репост"><i class="icon-repeat"></i></a><? } else { ?><i class="icon-repeat"></i><? } ?> <?=MyHtmlHelper::formateNumberword($tweet->retweets,'репостов','репост','репоста')?> <? if ($tweet->uid === $viewer_id) { ?><button class="btn btn-danger btn-small delete delete_tweet" title="Удалить" data-id="<?=$tweet->id?>" >X</button><? } ?></p>
                 </div>
               <? } ?>
                  <button class="btn btn-block" id="update_tag_feed" data-time="<?=$timeFeedGenerated?>" data-offset="3" data-tag="<?=$tag?>" >Далее</button>
                </div>
                <div class="span4" id="twitter_popular_feed">
                <h4>Популярные посты</h4>
               <? foreach ($feed as $i => $tweet) { ?>
                 <div class="tweet <? if ($i === count($feed)-1) { ?>last<? } ?>">
                 <strong><a href="#" onclick="load_page('twitter',{'uid':<?=$tweet->uid?>})"><?=htmlspecialchars($tweet->user->name)?></a></strong> <span class="date prettyDate" data-unixtime="<?=$tweet->date?>"><?=date('d-M-Y H:i',$tweet->date)?></span>
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
   

    $('#update_feed').click(function(){
      $(this).attr("disabled","disabled");
      var offset = parseInt($(this).data('offset'));
      get_html('get-twitter-feed',{'time':$(this).data('time'),'offset':offset},function(rows){

          $('#twitter_popular_feed .last').removeClass('last');
          $('#twitter_popular_feed').append(rows);
          $('#twitter_popular_feed').append($('#update_feed'));
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

  })
    </script>