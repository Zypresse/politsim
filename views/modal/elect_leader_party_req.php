<p><strong>Выборы лидера организации <?=htmlspecialchars($org->name)?></strong></p>
<p>Выберите кандидата на должность «<?=htmlspecialchars($org->leader->name)?>»: 
<select class="candidat" id="candidat<?=$org->id?>">
<? foreach ($user->party->members as $member) { ?>
	<option value="<?=$member->id?>"><?=htmlspecialchars($member->name)?></option>
<? } ?>
</select>
<!--{% if child_orgs.length %}<p>Есть организации, лидер которых выбирается вместе с ним:
<ul>
	{% for child_org in child_orgs %}
<li>Кандидат на должность «{{ child_org.post_name|escape }}» в организации «{{ child_org.name|escape }}»
<select class="candidat" id="candidat{{ child_org.id }}">
</select>
</li>
	{% endfor %}
</ul>
</p>{% endif %}-->

<script>
	

send_elect_request = function() {
	/*{% for child_org in child_orgs %}
		if ($('#candidat{{ id }}').val() == $('#candidat{{ child_org.id }}').val()) {
			alert('Нельзя подавать заявку от одного кандидата на несколько должностей сразу')
			return false;

		}
	{% endfor %}*/

	json_request('elect_request',{'org_id':<?=$org->id?>,'leader':1,'candidat':$('#candidat<?=$org->id?>').val()});

	/*{% for child_org in child_orgs %}
	json_request('elect_request',{'org_id':{{ child_org.id }},'leader':1,'candidat':$('#candidat{{ child_org.id }}').val()});
	{% endfor %}*/
}
</script>