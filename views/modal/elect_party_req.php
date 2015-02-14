<p><strong>Выборы в организацию <?=htmlspecialchars($org->name)?></strong></p>
<!--{% if child_orgs.length %}<p>Есть организации, выборы в которые проводятся вместе с этими:
<ul>
	{% for child_org in child_orgs %}
<li>{{ child_org.name|escape }}</li>
	{% endfor %}
</ul>
</p>{% endif %}-->
<script>
send_elect_request = function() {
	json_request('elect-request',{'org_id':<?=$org->id?>,'leader':0});
}
</script>