<?

	use app\components\MyHtmlHelper;

	$is_citizen = ($user->state_id === $state->id);

	$votes = ['e'=>false,'el'=>false,'l'=>false,'ll'=>false];
	foreach ($user->votes as $vote) {
		if ($vote->request) {
		switch (true) {
			case ($vote->request->org_id === $state->executive && !$vote->request->leader):
				$votes['e'] = true;
			break;
			case ($vote->request->org_id === $state->executive && $vote->request->leader):
				$votes['el'] = true;
			break;
			case ($vote->request->org_id === $state->legislature && !$vote->request->leader):
				$votes['l'] = true;
			break;
			case ($vote->request->org_id === $state->legislature && $vote->request->leader):
				$votes['ll'] = true;
			break;
		}}
	}
        
	$requests = ['e'=>false,'el'=>false,'l'=>false,'ll'=>false];
	if ($user->party) {
            foreach ($user->party->requests as $request) {
		switch (true) {
			case ($request->org_id === $state->executive):
				$requests['e'] = true;
			break;
			case ($request->org_id === $state->legislature):
				$requests['l'] = true;
			break;
		}
            }
            foreach ($user->party->lrequests as $request) {
		switch (true) {
			case ($request->org_id === $state->executive):
				$requests['el'] = true;
			break;
			case ($request->org_id === $state->legislature):
				$requests['ll'] = true;
			break;
		}
            }
	}

	foreach ($user->requests as $request) {
		switch (true) {
			case ($request->org_id === $state->executive && !$request->leader):
				$requests['e'] = true;
			break;
			case ($request->org_id === $state->executive && $request->leader):
				$requests['el'] = true;
			break;
			case ($request->org_id === $state->legislature && !$request->leader):
				$requests['l'] = true;
			break;
			case ($request->org_id === $state->legislature && $request->leader):
				$requests['ll'] = true;
			break;
		}
	}


?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
<h3>Выборы в государстве <a href="#" onclick="load_page('state-info',{'id':<?=$state->id?>})"><?=htmlspecialchars($state->name)?></a></h3>
<div style="display:none" class="modal" id="old-elections" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Прошлые выборы</h3>
  </div>
  
  <div id="old-elections_body" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<button class="btn btn-default" onclick="load_modal('old-elections',{'state_id':<?=$state->id?>},'old-elections','old-elections_body')">Результаты прошлых выборов</button><br><br>

<? if ($state->executiveOrg && $state->executiveOrg->isElected()) { ?>
<? if ($state->executiveOrg->isGoingElects()) { ?>
<p>
Сейчас идут выборы в огранизацию «<a href="#" onclick="load_page('org-info',{'id':<?=$state->executive?>});"><?=htmlspecialchars($state->executiveOrg->name)?></a>» и будут длиться до <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect)?></span><br>
<? if ($is_citizen) { ?>
<button <? if ($votes['e']) { ?>disabled="disabled" title="Вы уже проголосовали"<? } ?> class="btn btn-primary" onclick="elect_vote(<?=$state->executive?>,0)">Проголосовать</button>
<? } ?>
<button class="btn btn-lightblue" onclick="elect_exitpolls(<?=$state->executive?>,0)">Результаты эксит-поллов</button><br>
</p><br>
<? } else { ?>
<p>Следующие выборы в огранизацию «<a href="#" onclick="load_page('org-info',{'id':<?=$state->executive?>});"><?=htmlspecialchars($state->executiveOrg->name)?></a>» пройдут с <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect-24*60*60?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect-24*60*60)?></span> по <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect)?></span><br>
<? if ($state->executiveOrg->dest === 'nation_party_vote' && $is_citizen && $user->isPartyLeader() && !$requests['e']) { ?>
<button class="btn btn-green" onclick="elect_request(<?=$state->executive?>,0)">Подать заявку на выборы от партии</button><br>
<? } ?>
<? if ($requests['e'] && $user->isPartyLeader()) { ?>
<button class="btn btn-red" onclick="drop_elect_request(<?=$state->executive?>,0)">Отозвать заявку на выборы</button><br>
<? } ?>

<? if (count($state->executiveOrg->requests)) { ?><strong>Список подавших заявку на выборы:</strong><ul><? foreach ($state->executiveOrg->requests as $request) { ?>
<li><a href="#" onclick="load_page('party-info',{'id':<?=$request->party_id?>})"><?=htmlspecialchars($request->party->name)?></a></li>
<? } ?></ul><? } else { ?><strong>Никто ещё не подал заявку на выборы</strong><? } ?>
</p><br><? } ?><? } ?>

<? if ($state->executiveOrg && $state->executiveOrg->isLeaderElected()) { ?>
<? if ($state->executiveOrg->isGoingElects()) { ?>
<p>
Сейчас идут выборы лидера огранизации «<a href="#" onclick="load_page('org-info',{'id':<?=$state->executive?>});"><?=htmlspecialchars($state->executiveOrg->name)?></a>» и будут длиться до <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect)?></span><br>
<? if ($is_citizen) { ?>
<button <? if ($votes['el']) { ?>disabled="disabled" title="Вы уже проголосовали"<? } ?> class="btn btn-primary" onclick="elect_vote(<?=$state->executive?>,1)">Проголосовать</button>
<? } ?>
<button class="btn btn-lightblue" onclick="elect_exitpolls(<?=$state->executive?>,1)">Результаты эксит-поллов</button><br>
</p><br>
<? } else { ?>
<p>Следующие выборы лидера организации «<a href="#" onclick="load_page('org-info',{'id':<?=$state->executive?>});"><?=htmlspecialchars($state->executiveOrg->name)?></a>» пройдут с <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect-24*60*60?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect-24*60*60)?></span> по <span class="formatDate" data-unixtime="<?=$state->executiveOrg->next_elect?>"><?=date('d-M-Y H:i',$state->executiveOrg->next_elect)?></span><br>
<? if ($is_citizen && !$requests['el']) { if ($state->executiveOrg->leader_dest === 'nation_party_vote' && $user->isPartyLeader()) { ?>
<button class="btn btn-blue" onclick="elect_request(<?=$state->executive?>,1)">Подать заявку на выборы от партии</button>
<? } elseif ($state->executiveOrg->leader_dest === 'nation_individual_vote') { ?>
<button class="btn btn-blue" onclick="elect_request(<?=$state->executive?>,1)">Подать заявку на выборы</button>
<? } ?><br><? } ?>
<? if ($requests['el'] && ($user->isPartyLeader() || $state->executiveOrg->leader_dest === 'nation_individual_vote')) { ?>
<button class="btn btn-red" onclick="drop_elect_request(<?=$state->executive?>,1)">Отозвать заявку на выборы</button><br>
<? } ?>

<? if (count($state->executiveOrg->lrequests)) { ?><strong>Список подавших заявку на выборы:</strong><ul><? foreach ($state->executiveOrg->lrequests as $request) { ?>
<li><a href="#" onclick="load_page('profile',{'uid':<?=$request->candidat?>})"><?=htmlspecialchars($request->user->name)?></a> <? if ($state->executiveOrg->leader_dest === 'nation_party_vote') { ?>(<?=$request->user->party->short_name?>)<? } ?></li>
<? } ?></ul><? } else { ?><strong>Никто ещё не подал заявку на выборы</strong><? } ?>
</p><br><? } ?><? } ?>



<? if ($state->legislatureOrg && $state->legislatureOrg->isElected()) { ?>
<? if ($state->legislatureOrg->isGoingElects()) { ?>
<p>
Сейчас идут выборы в огранизацию «<a href="#" onclick="load_page('org-info',{'id':<?=$state->legislature?>});"><?=htmlspecialchars($state->legislatureOrg->name)?></a>» и будут длиться до <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect)?></span><br>
<? if ($is_citizen) { ?>
<button <? if ($votes['l']) { ?>disabled="disabled" title="Вы уже проголосовали"<? } ?> class="btn btn-primary" onclick="elect_vote(<?=$state->legislature?>,0)">Проголосовать</button>
<? } ?>
<button class="btn btn-lightblue" onclick="elect_exitpolls(<?=$state->legislature?>,0)">Результаты эксит-поллов</button><br>
</p><br>
<? } else { ?>
<p>Следующие выборы в огранизацию «<a href="#" onclick="load_page('org-info',{'id':<?=$state->legislature?>});"><?=htmlspecialchars($state->legislatureOrg->name)?></a>» пройдут с <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect-24*60*60?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect-24*60*60)?></span> по <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect)?></span><br>
<? if ($state->legislatureOrg->dest === 'nation_party_vote' && $is_citizen && $user->isPartyLeader() && !$requests['l']) { ?>
<button class="btn btn-blue" onclick="elect_request(<?=$state->legislature?>,0)">Подать заявку на выборы от партии</button><br>
<? } ?>
<? if ($requests['l'] && $user->isPartyLeader()) { ?>
<button class="btn btn-red" onclick="drop_elect_request(<?=$state->legislature?>,0)">Отозвать заявку на выборы</button><br>
<? } ?>

<? if (count($state->legislatureOrg->requests)) { ?><strong>Список подавших заявку на выборы:</strong><ul><? foreach ($state->legislatureOrg->requests as $request) { ?>
<? if ($request->party) {?><li><a href="#" onclick="load_page('party-info',{'id':<?=$request->party_id?>})"><?=htmlspecialchars($request->party->name)?></a></li><? } else {$request->delete();} ?>
<? } ?></ul><? } else { ?><strong>Никто ещё не подал заявку на выборы</strong><? } ?>
</p><br><? } ?><? } ?>




<? if ($state->legislatureOrg && $state->legislatureOrg->isLeaderElected()) { ?>
<? if ($state->legislatureOrg->isGoingElects()) { ?>
<p>
Сейчас идут выборы лидера огранизации «<a href="#" onclick="load_page('org-info',{'id':<?=$state->legislature?>});"><?=htmlspecialchars($state->legislatureOrg->name)?></a>» и будут длиться до <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect)?></span><br>
<? if ($is_citizen) { ?>
<button <? if ($votes['ll']) { ?>disabled="disabled" title="Вы уже проголосовали"<? } ?> class="btn btn-primary" onclick="elect_vote(<?=$state->legislature?>,1)">Проголосовать</button>
<? } ?>
<button class="btn btn-lightblue" onclick="elect_exitpolls(<?=$state->legislature?>,1)">Результаты эксит-поллов</button><br>
</p><br>
<? } else { ?>
<p>Следующие выборы лидера организации «<a href="#" onclick="load_page('org-info',{'id':<?=$state->legislature?>});"><?=htmlspecialchars($state->legislatureOrg->name)?></a>» пройдут с <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect-24*60*60?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect-24*60*60)?></span> по <span class="formatDate" data-unixtime="<?=$state->legislatureOrg->next_elect?>"><?=date('d-M-Y H:i',$state->legislatureOrg->next_elect)?></span><br>
<? if ($is_citizen && !$requests['ll']) { if ($state->legislatureOrg->leader_dest === 'nation_party_vote' && $user->isPartyLeader()) { ?>
<button class="btn btn-blue" onclick="elect_request(<?=$state->legislature?>,1)">Подать заявку на выборы от партии</button>
<? } elseif ($state->legislatureOrg->leader_dest === 'nation_individual_vote') { ?>
<button class="btn btn-blue" onclick="elect_request(<?=$state->legislature?>,1)">Подать заявку на выборы</button>
<? } ?><br><? } ?>
<? if ($requests['ll'] && ($user->isPartyLeader() || $state->legislatureOrg->leader_dest === 'nation_individual_vote')) { ?>
<button class="btn btn-red" onclick="drop_elect_request(<?=$state->legislature?>,1)">Отозвать заявку на выборы</button><br>
<? } ?>

<? if (count($state->legislatureOrg->lrequests)) { ?><strong>Список подавших заявку на выборы:</strong><ul><? foreach ($state->legislatureOrg->lrequests as $request) { ?>
<li><a href="#" onclick="load_page('profile',{'uid':<?=$request->candidat?>})"><?=htmlspecialchars($request->user->name)?></a> <? if ($state->legislatureOrg->leader_dest === 'nation_party_vote') { ?>(<?=$request->user->party->short_name?>)<? } ?></li>
<? } ?></ul><? } else { ?><strong>Никто ещё не подал заявку на выборы</strong><? } ?>
</p><? } ?><? } ?>
        </div>
    </div>
</div>

<div style="display:none" class="modal" id="elect_request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel1">Заявка на выборы</h3>
  </div>
  
  <div id="elect_request_body" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button class="btn btn-green" data-dismiss="modal" aria-hidden="true" onclick="send_elect_request()">Отправить</button>
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none" class="modal" id="elect_vote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel2">Бюллетень</h3>
  </div>
  
  <div id="elect_vote_body" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button id="vote_button" style="display:none;" class="btn btn-blue" data-dismiss="modal" aria-hidden="true" onclick="send_elect_vote()">Проголосовать</button>
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>

<div style="display:none" class="modal" id="elect_exitpolls" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel3">Результаты эксит-поллов</h3>
  </div>
  
  <div id="elect_exitpolls_body" class="modal-body">
    
  </div>
  <div class="modal-footer">
    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
  </div>
</div>
<script>
var org_id,leader,candidat,request_id;

var send_elect_request = function() {
	json_request('elect-request',{'org_id':org_id,'leader':leader,'candidat':candidat});
}
var send_elect_vote = function() {
    if (request_id) json_request('elect-vote',{'request':parseInt(request_id)});
}

function drop_elect_request(org_id,leader) {
	json_request('drop-elect-request',{'org_id':org_id,'leader':leader,'party_id':<?=$user->party_id?>});
}

function elect_request(Torg_id,Tleader) {
	org_id = Torg_id;
	leader = Tleader;
        load_modal('elect-request',{'org_id':org_id,'leader':leader},'elect_request','elect_request_body');
}

function elect_vote(org_id,leader) {
        load_modal('elect-vote',{'org_id':org_id,'leader':leader},'elect_vote','elect_vote_body');
}

function elect_exitpolls(org_id,leader) {
        load_modal('elect-exitpolls',{'org_id':org_id,'leader':leader},'elect_exitpolls','elect_exitpolls_body');
}
</script>