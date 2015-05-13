<?php

/* 
 * Copyleft license
 * I dont care how you use it
 */

use app\components\MyHtmlHelper,
    yii\helpers\Html,
    app\models\FactoryCategory,
    app\models\HoldingDecision;

$userStock = $user->getShareholderStock($holding);
$factoryCategories = FactoryCategory::find()->all();

?>
<h1>Управление «<?=$holding->name?>»</h1>
<p>Капитализация: <?=number_format($holding->capital,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<p>Баланс: <?=number_format($holding->balance,0,'',' ')?> <?=MyHtmlHelper::icon('money')?></p>
<? if ($holding->state) { ?>
    <p>Компания зарегистрирована в государстве: <?=Html::a($holding->state->name,'#',['onclick'=>"load_page('state-info',{'id':{$holding->state_id}})"])?></p>
<? } ?>
<? if ($holding->region) { ?>
    <p>Компания имеет штаб-квартиру в регионе: <?=$holding->region->name?></p>
<? } else { ?>
    <p>Компания не имеет штаб-квартиры</p>
<? } ?>
<h3>Лицензии:</h3>
<? if (count($holding->licenses)) { ?>
<button class="btn btn-default" id="list_licenses_button" >Развернуть/свернуть список</button>
<ul id="list_licenses" style="display: none" >
     <? foreach ($holding->licenses as $license) { ?>
    <li>
            <?=$license->type->name?> (<?=$license->state->name?>)
    </li>
     <? } ?>
</ul>
<? } else { ?>
<p>Компания не обладает лицензией ни на один вид деятельности</p>
<? } ?>
<h3>Недвижимость</h3>
<? if (count($holding->factories)) { ?>
<ul>
    <? foreach ($holding->factories as $factory) { ?>
    <li>
        <?=Html::a($factory->name,'#',['onclick'=>"load_page('factory-info',{'id':{$factory->id}})"])?> 
            <? if ($factory->status < 0) { ?><span style="color:red;">(не достроено, запланированная дата окончания строительства: <span class="formatDate" data-unixtime="<?=$factory->builded?>"><?=date('d-M-Y H:i',$factory->builded)?></span>)</span><? } ?>
            <? if ($factory->status > 1) { ?><span style="color:red;">(не работает)</span><? } ?>
    </li>
    <? } ?>
</ul>
<? } else { ?>
<p>Компания не владеет недвижимостью</p>
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
                    echo ($stock->master->ministry_name ? $stock->master->ministry_name : $stock->master->name).' ('.Html::a($stock->master->org->name,'#',['onclick'=>"load_page('org-info',{'id':{$stock->master->org_id}})"]).')';
                break;
                case 'app\models\Holding':
                    echo Html::a($stock->master->name,'#',['onclick'=>"load_page('holding-info',{'id':{$stock->master->id}})"]);
                break;
        }?>
        <?=round($stock->getPercents(),2)?>%
    </li>
    <? } ?>
</ul>
<h3>Решения на голосовании:</h3>
<? if (count($holding->decisions)) { ?>
<table class="table">
<?
foreach ($holding->decisions as $decision) {
    $data = json_decode($decision->data);
?>
    <tr>
        <td><?=date('d-m-Y',$decision->created)?></td>
        <td><? switch ($decision->decision_type) {
            case HoldingDecision::DECISION_CHANGENAME:
                echo 'Переименование холдинга в «'.$data->new_name.'»';
                break;
            case HoldingDecision::DECISION_PAYDIVIDENTS:
                echo 'Выплата дивидентов в размере '.$data->sum.' '.MyHtmlHelper::icon('money');
                break;
            case HoldingDecision::DECISION_GIVELICENSE:
                $license = app\models\HoldingLicenseType::findByPk($data->license_id);
                echo 'Получение лицензии на «'.$license->name.'»';
                break;
            case HoldingDecision::DECISION_BUILDFABRIC:
                $fType = app\models\FactoryType::findByPk($data->factory_type);
                $region = app\models\Region::findByPk($data->region_id);
                echo "Строительство нового обьекта: {$fType->name} под названием «{$data->name}» в регионе {$region->name}";
                break;
            case HoldingDecision::DECISION_SETMANAGER:
                $user = app\models\User::findByPk($data->uid);
                $factory = app\models\Factory::findByPk($data->factory_id);
                $region_name = $factory->region->name. ($factory->region->state ? ', '.$factory->region->state->short_name : '');
                echo "Назначение человека по имени {$user->name} на должность управляющего обьектом {$factory->name} ({$region_name})";
                break;
            case HoldingDecision::DECISION_SETMAINOFFICE:
                $factory = app\models\Factory::findByPk($data->factory_id);
                $region_name = $factory->region->name. ($factory->region->state ? ', '.$factory->region->state->short_name : '');
                echo "Назначение офиса {$factory->name} ({$region_name}) главным офисом компании";
                break;
            case HoldingDecision::DECISION_RENAMEFABRIC:
                $factory = app\models\Factory::findByPk($data->factory_id);
                $region_name = $factory->region->name. ($factory->region->state ? ', '.$factory->region->state->short_name : '');
                echo "Переименование объекта {$factory->name} ({$region_name}) в {$data->new_name}";
                break;
        }
        ?></td><td>
            <?
            $za = 0; $protiv = 0;
            foreach ($decision->votes as $vote) {
                if (intval($vote->variant) === 1) {
                    $za += ($vote->stock) ? $vote->stock->getPercents() : 0;
                } elseif (intval($vote->variant) === 2) {
                    $protiv += ($vote->stock) ? $vote->stock->getPercents() : 0;
                }
            }
            ?>
            <span style="color:green"><?=round($za,2)?>% акций ЗА</span>, <span style="color:red"><?=round($protiv,2)?>% акций ПРОТИВ</span>
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
    <li><a href="#" onclick="$('#set_main_office_modal').modal();" >Установить главный офис</a></li>
  </ul>
</div>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-success" data-toggle="dropdown">
    Управление счётом <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#" onclick="$('#stock_dividents_modal').modal();" >Выплатить дивиденты</a></li>
    <li><a href="#" onclick="$('#insert_money_modal').modal();" >Внести деньги на счёт</a></li>
  </ul>
</div>
<div class="btn-group">
  <button class="btn btn-small dropdown-toggle btn-success" data-toggle="dropdown">
    Управление недвижимостью <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#" onclick="$('#new_factory_modal').modal();" >Построить новый обьект</a></li>
    <li><a href="#" onclick="$('#set_manager_modal').modal();" >Назначить управляющего</a></li>
    <li><a href="#" onclick="$('#rename_factory_modal').modal();" >Переименовать обьект</a></li>
  </ul>
</div>
    <? if ($holding->state) { ?>
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
                    
                    foreach ($holding->state->licenses as $sl) {
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
    <? } else { ?>
<p style="color:red;">Компания зарегистрирована в несущесвующем ныне государстве!</p>
    <? } ?>
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
<div style="display:none;" class="modal" id="set_manager_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1432" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1233">Выплата дивидентов акционерам</h3>
  </div>
  <div id="set_manager_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#new_manager_factory">Объект недвижимсти:</label>
      <div class="controls">
          <select id="new_manager_factory">
              <? foreach ($holding->factories as $factory) { ?>
              <option value="<?=$factory->id?>"><?=$factory->name?> (<?=$factory->region->name?>)</option>
              <? } ?>
          </select>
      </div>
      <label class="control-label" for="#new_manager_uid">Новый управляющий:</label>
      <div class="controls">
          <select id="new_manager_uid">
              <? foreach ($holding->stocks as $stock) { ?>
                    <? switch (get_class($stock->master)) {
                            case 'app\models\User':
                                echo "<option value='{$stock->master->id}'>".Html::a(Html::img($stock->master->photo,['style'=>'width:20px']).' '.$stock->master->name,"#",['onclick'=>"load_page('profile',{'uid':{$stock->user_id}})"])."</option>";
                            break;
                            case 'app\models\Post':
                                echo "<option value='{$stock->master->user->id}'>".Html::a(Html::img($stock->master->user->photo,['style'=>'width:20px']).' '.$stock->master->user->name,"#",['onclick'=>"load_page('profile',{'uid':{$stock->master->user->id}})"])."</option>";
                            break;
                            case 'app\models\Holding':
                                
                            break;
                    }?>
              <? } ?>
          </select>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal"  onclick="json_request('new-holding-decision',{'holding_id':<?=$holding->id?>,'factory_id':$('#new_manager_factory').val(),'uid':$('#new_manager_uid').val(),'type':6})">Назначить</button>
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
          <input type="text" id="holding_new_name" value="<?=  htmlspecialchars($holding->name)?>">
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="rename_holding(<?=$holding->id?>)">Переименовать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none;" class="modal" id="rename_factory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1234">Переименование обьекта</h3>
  </div>
  <div id="rename_factory_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#factory_id_for_rename">Обьект</label>
      <div class="controls">
          <select id="factory_id_for_rename">
              <? foreach ($holding->factories as $factory) {?>
              <option value="<?=$factory->id?>"><?=$factory->name?> (<?=$factory->region->name?>)</option>
              <? } ?>
          </select>
      </div>
      <label class="control-label" for="#factory_new_name">Название</label>
      <div class="controls">
          <input type="text" id="factory_new_name" value="">
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="rename_factory()">Переименовать</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none;" class="modal" id="set_main_office_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel123" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1234">Установка главного офиса</h3>
  </div>
  <div id="set_main_office_modal_body" class="modal-body">
    <div class="control-group">
      <label class="control-label" for="#new_main_office_id">Обьект</label>
      <div class="controls">
          <select id="new_main_office_id">
              <? foreach ($holding->factories as $factory) {
                  if ($factory->type_id == 4) { ?>
              <option value="<?=$factory->id?>"><?=$factory->name?> (<?=$factory->region->name?>)</option>
                  <? }
              } ?>
          </select>
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<button class="btn btn-primary" data-dismiss="modal"  onclick="set_main_office()">Установить</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none;" class="modal" id="new_factory_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabelnew_factory_modal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Строительство</h3>
  </div>
  <div id="new_factory_modal_body" class="modal-body">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Тип обьекта</h3>
            <span class="pull-right">
                <!-- Tabs -->
                <ul class="nav panel-tabs">
                    <? foreach ($factoryCategories as $i => $factoryCat) { ?>
                    <li class="<?=($i != 5)?'hide':'active'?>"><a href="#tab<?=$i?>" data-toggle="tab"><?=Html::img("/img/factory-types/{$factoryCat->id}.png",['alt'=>$i,'title'=>$factoryCat->name])?></a></li>
                    <? } ?>
                </ul>
            </span>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <? foreach ($factoryCategories as $i => $factoryCat) { ?>
                    <div class="tab-pane <?=($i != 5)?'':'active'?>" id="tab<?=$i?>">
                        <h3><?=$factoryCat->name?></h3>
                        <? foreach($factoryCat->types as $facType) { ?>
                            <p>
                                <input data-workersSize="<?=$facType->sumNeedWorkers?>" data-buildCost="<?=$facType->build_cost?>" class="elect_vote_radio" type="radio" name="new_factory_type" value="<?=$facType->id?>" id="new_factory_type<?=$facType->id?>">
                                <label style="display: inline-block;" for="new_factory_type<?=$facType->id?>"><?=$facType->name?></label>
                            </p>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="control-group" id="new_factory_add_info" style="display:none;">
      <label class="control-label" for="#factory_new_name">Название</label>
      <div class="controls">
          <input type="text" id="factory_new_name" value="">
      </div>
      <label class="control-label" for="#factory_new_region">Место строительства</label>
      <div class="controls">
          <select id="factory_new_region">
              <?
              $regions = app\models\Region::find()->with('state')->orderBy('state_id')->all();
               foreach ($regions as $i => $region) { ?>
              <? if ($i == 0 || $regions[$i-1]->state_id != $region->state_id) { ?>
                <?=($i)?'</optgroup>':''?><optgroup label="<?=($region->state) ? $region->state->name : 'Ничейные регионы'?>">
              <? } ?>
              <option value="<?=$region->id?>"><?=$region->name?></option>
              <? } ?>
          </select>
      </div>
      <label class="control-label" for="#factory_new_size">Размер</label>
      <div class="controls">
          <button class="btn btn-mini" onclick="if ($('#factory_new_size').val()>1) $('#factory_new_size').val(parseInt($('#factory_new_size').val()) - 1); updateCost();">-</button><input class="btn btn-mini" type="range" id="factory_new_size" min="1" max="127" step="1" value="1"><button class="btn btn-mini" onclick="if ($('#factory_new_size').val() < 127) $('#factory_new_size').val(parseInt($('#factory_new_size').val()) + 1); updateCost()">+</button>
      </div>
      
      <p>Число работников: <span id="workers_size">0</span> <i class="icon-user"></i></p>
      <p>Стоимость строительства: <span id="build_cost">0</span> <?=MyHtmlHelper::icon('coins')?></p>
    </div>
      
      
  </div>
  <div class="modal-footer">
    <button style="display:none;" class="btn btn-primary" data-dismiss="modal" id="start_build" onclick="start_build()">Начать строительство</button>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<script>
function rename_holding(id) {
    if ($('#holding_new_name').val()) {
        json_request('new-holding-decision',{'holding_id':id,'type':1,'new_name':$('#holding_new_name').val()});
    }
}

function vote_for_decision(id,variant) {
    json_request('vote-for-decision',{'decision_id':id,'variant':variant});
}

function pay_dividents(id) {
    if ($('#dividents_sum').val()) {
        json_request('new-holding-decision',{'holding_id':id,'type':2,'sum':$('#dividents_sum').val()});
    }
}

function insert_money(id) {
    if ($('#insert_sum').val()) {
        if (confirm("Вы действительно безвозмездно внести деньги на счёт фирмы?")) {
            json_request('insert-money-to-holding',{'holding_id':id,'sum':$('#insert_sum').val()});
        }
    }
}

function get_new_license(id) {
    json_request('new-holding-decision',{'holding_id':id,'type':3,'license_id':$('#new_license_id').val()});
}

function updateLicenseInfo() {
   $('#license_info').html($("#license_option"+$('#new_license_id').val()).data('text'));
}

function rename_factory() {
    json_request('new-holding-decision',{'holding_id':<?=$holding->id?>,'type':8,'factory_id':$('#factory_id_for_rename').val(),'new_name':$('#factory_new_name').val()});
}

function set_main_office() {
    json_request('new-holding-decision',{'holding_id':<?=$holding->id?>,'type':7,'factory_id':$('#new_main_office_id').val()});
}

var new_factory_type = 0;

function updateCost(){
    var size = parseInt($('#factory_new_size').val());
    var workers = $('#new_factory_type'+new_factory_type).attr("data-workersSize");
    var cost = $('#new_factory_type'+new_factory_type).attr("data-buildCost");

    $('#workers_size').text(size*workers);
    $('#build_cost').text(size*cost);
    return true;
}

function start_build() {
    var cost = parseInt($('#factory_new_size').val()) * parseInt($('#new_factory_type'+new_factory_type).attr("data-buildCost"));
    if (cost > <?=$holding->balance?>) {
        alert("На счету фирмы недостаточно денег для строительства");            
    } else {
        json_request('new-holding-decision',{
            'holding_id':<?=$holding->id?>,
            'type':5,
            'name':$('#factory_new_name').val(),
            'region_id':$('#factory_new_region').val(),
            'factory_type':new_factory_type,
            'size': $('#factory_new_size').val()
        });
    }
}

$(function(){
    updateLicenseInfo();
    $('#new_license_id').change(updateLicenseInfo);

    $('#dividents_sum').change(function(){
        if ($(this).val()<=<?=count($holding->stocks)?>) {
            $(this).val(<?=count($holding->stocks)?>);
        } 
        if ($(this).val()><?=$holding->balance?>) {
            $(this).val(<?=$holding->balance?>);
        } 
    });
    
    $('#list_licenses_button').toggle(function() {
        $('#list_licenses').slideDown();
    }, function() {
        $('#list_licenses').slideUp();
    })
    
    $('.elect_vote_radio').iCheck({
        checkboxClass: 'icheckbox_square',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    }).on('ifChecked', function(event){
      
      new_factory_type = $(this).val();
      $('#new_factory_add_info').show();
      $('#start_build').show();
      updateCost();
    });;
    
    $('#factory_new_size').change(updateCost);
    $('#factory_new_size').click(updateCost);
    
});
</script>