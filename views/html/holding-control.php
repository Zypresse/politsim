<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper;
use yii\helpers\Html;

$userStock = $user->getShareholderStock($holding);

?>
<h1>Управление «<?=$holding->name?>»</h1>
<p>Капитализация: <?=number_format($holding->capital,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<p>Баланс: <?=number_format($holding->balance,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<h3>Лицензии:</h3>
<? if (sizeof($holding->licenses)) { ?>
<ul>
     <? foreach ($holding->licenses as $license) { ?>
    <li>
            <?=$license->type->name?>
    </li>
     <? } ?>
</ul>
<? } else { ?>
<p>Компания не обладает лицензией ни на один вид деятельности</p>
<? } ?>
<h3>Список акционеров:</h3>
<ul>
    <? foreach ($holding->stocks as $stock) { ?>
    <li>
        <? switch (get_class($stock->master)) {
                case 'app\models\User':
                    echo Html::a(Html::img($stock->master->photo,['style'=>'width:20px']).' '.$stock->master->name,"#",['onclick'=>"load_page('profile',{'uid':{$stock->user_id}})"]);
                break;
                case 'app\models\Post':
                    echo $stock->master->name.' ('.Html::a($stock->master->org->name,'#',['onclick'=>"load_page('org-info',{'id':{$stock->master->org_id}})"]).')';
                break;
                case 'app\models\Holding':
                    echo Html::a($stock->master->name,'#',['onclick'=>"load_page('holding-info',{'id':{$stock->master->id}})"]);
                break;
        }?>
        <?=$stock->getPercents()?>%
    </li>
    <? } ?>
</ul>
<h3>Решения на голосовании:</h3>
<? if (sizeof($holding->decisions)) { ?>
<table class="table">
<?
foreach ($holding->decisions as $decision) {
    $data = json_decode($decision->data);
?>
    <tr>
        <td><?=date('d-m-Y',$decision->created)?></td>
        <td><? switch ($decision->decision_type) {
            case 1:
                echo 'Переименование холдинга в «'.$data->new_name.'»';
            break;
            case 2:
                echo 'Выплата дивидентов в размере '.$data->sum.' '.MyHtmlHelper::icon('money');
            break;
            case 3:
                $license = app\models\HoldingLicenseType::findByPk($data->license_id);
                echo 'Получение лицензии на «'.$license->name.'»';
            break;
        }
        ?></td><td>
            <?
            $za = 0; $protiv = 0;
            foreach ($decision->votes as $vote) {
                if (intval($vote->variant) === 1) {
                    $za += $vote->stock->getPercents();
                } elseif (intval($vote->variant) === 2) {
                    $protiv += $vote->stock->getPercents();
                }
            }
            ?>
            <span style="color:green"><?=$za?>% акций ЗА</span>, <span style="color:red"><?=$protiv?>% акций ПРОТИВ</span>
        </td>
        <td>
            <?  
            $allreadyVoted = false;
            foreach ($decision->votes as $vote) { 
                if ($vote->stock_id === $userStock->id) {
                    $allreadyVoted = true;
                }
            }
            if ($allreadyVoted) {
                echo "Вы уже проголосовали";
            } else {
            ?>
            <button class="btn btn-success" onclick="vote_for_decision(<?=$decision->id?>,1)">ЗА</button>
            <button class="btn btn-danger" onclick="vote_for_decision(<?=$decision->id?>,2)">ПРОТИВ</button>
            <?
            }
            ?>
        </td>
    </tr>        
<?
}
?>
</table>
<? } else { ?>
<p>Нет решений на голосовании</p>
<? } ?>

<div class="btn-toolbar">
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-primary" data-toggle="dropdown">
    Общие предложения <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <li><a href="#" onclick="$('#rename_holding_modal').modal();" >Переименовать холдинг</a></li>
    <li><a href="#" onclick="$('#stock_dividents_modal').modal();" >Выплатить дивиденты</a></li>
  </ul>
</div>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-success" data-toggle="dropdown">
    Управление счётом <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <li><a href="#" onclick="$('#insert_money_modal').modal();" >Внести деньги на счёт</a></li>
  </ul>
</div>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-success" data-toggle="dropdown">
    Управление лицензиями <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <!--<li class="divider"></li>-->
    <li><a href="#" onclick="$('#new_license_modal').modal();" >Получить лицензию на новый вид деятельности</a></li>
  </ul>
</div>
</div>

<div style="display:none;" class="modal" id="new_license_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1231">Получение лицензии</h3>
  </div>
  <div id="new_license_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#dividents_sum">Лицензия</label>
      <div class="controls">
          <select id="new_license_id">
              <? 
                $licenses = app\models\HoldingLicenseType::find()->all();
                
                foreach ($licenses as $license) {
                    $stateLicense = null;
                    $allowed = true;
                    foreach ($holding->licenses as $hl) {
                        if ($license->id === $hl->license_id) {
                            $allowed = false;
                            $break;
                        }
                    }
                    if (!$allowed) continue;
                    
                    foreach ($user->state->licenses as $sl) {
                        if ($sl->license_id === $license->id) {
                            $stateLicense = $sl;
                            break;
                        }
                    }
                    $text = "Получение лицензии бесплатно";
                    if (!(is_null($stateLicense))) {
                        if ($stateLicense->is_only_goverment) {
                            if (!$userStock->post_id) {
                                $allowed = false;
                            }
                        }
                        if ($stateLicense->cost) {
                            $text = number_format($stateLicense->cost,0,'',' ').' '.MyHtmlHelper::icon('money');
                        }
                        if ($stateLicense->is_need_confirm) {
                            $text .= "<br>Необходимо подтверждение министра";
                        }
                    }
                    if ($allowed) {
                    ?>
              <option id="license_option<?=$license->id?>" value="<?=$license->id?>" data-text="<?=$text?>"><?=$license->name?></option>      
                <? }} ?>
          </select>
      </div>
      <p id="license_info"></p>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="get_new_license(<?=$holding->id?>)">Получить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<div style="display:none;" class="modal" id="insert_money_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1232">Выплата дивидентов акционерам</h3>
  </div>
  <div id="insert_money_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#dividents_sum">Сумма для внесения на счёт</label>
      <div class="controls">
        <input type="number" id="insert_sum" value="0"> <?=MyHtmlHelper::icon('money')?>
      </div>
    </div>
      <p>Деньги будут сняты с вашего счёта и внесены на баланс компании. Снять их будет проблематично, если вы не владеете 100% акций.</p>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="insert_money(<?=$holding->id?>)">Внести</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<div style="display:none;" class="modal" id="stock_dividents_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1233">Выплата дивидентов акционерам</h3>
  </div>
  <div id="stock_dividents_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#dividents_sum">Сумма для списания со счёта</label>
      <div class="controls">
        <input type="number" id="dividents_sum" value="<?=$holding->balance?>"> <?=MyHtmlHelper::icon('money')?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="pay_dividents(<?=$holding->id?>)">Выплатить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<div style="display:none;" class="modal" id="rename_holding_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1234">Переименование холдинга</h3>
  </div>
  <div id="rename_holding_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#holding_new_name">Название</label>
      <div class="controls">
        <input type="text" id="holding_new_name" value="<?=$holding->name?>">
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="rename_holding(<?=$holding->id?>)">Переименовать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<script>
function rename_holding(id) {
    json_request('new-holding-decision',{'holding_id':id,'type':1,'new_name':$('#holding_new_name').val()});
}

function vote_for_decision(id,variant) {
    json_request('vote-for-decision',{'decision_id':id,'variant':variant});
}

function pay_dividents(id) {
    json_request('new-holding-decision',{'holding_id':id,'type':2,'sum':$('#dividents_sum').val()});
}

function insert_money(id) {
    if (confirm("Вы действительно безвозмездно внести деньги на счёт фирмы?")) {
        json_request('insert-money-to-holding',{'holding_id':id,'sum':$('#insert_sum').val()});
    }
}

function get_new_license(id) {
    json_request('new-holding-decision',{'holding_id':id,'type':3,'license_id':$('#new_license_id').val()});
}

function updateLicenseInfo() {
   $('#license_info').html($("#license_option"+$('#new_license_id').val()).data('text'));
}

$(function(){
    updateLicenseInfo();
    $('#new_license_id').change(updateLicenseInfo);

    $('#dividents_sum').change(function(){
        if ($(this).val()<=<?=sizeof($holding->stocks)?>) {
            $(this).val(<?=sizeof($holding->stocks)?>);
        } else if ($(this).val()><?=$holding->balance?>) {
            $(this).val(<?=$holding->balance?>);
        }
    });
});
</script>