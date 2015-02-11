<p><strong>Выборы лидера организации <?=htmlspecialchars($org->name)?></strong></p>

<script>
send_elect_request = function() {
	json_request('elect_request',{'org_id':<?=$org->id?>,'leader':1,'candidat':<?=$user->id?>});
}
</script>