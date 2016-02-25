<?php switch ($gft->type) {
    case "checkbox":
        ?>
        <input <?=($gfv->value)?'checked="checked"':''?> type="checkbox" class="bill_field" id="bill_article_value" name="article_value">
        <?php 
        break;
    case "integer":
    case "number":
        ?>
        <input value="<?=$gfv->value?>" type="number" class="bill_field" id="bill_article_value" name="article_value">
        <?php break;
    case "org_dest_members":
        ?>
        <select class="bill_field" id="bill_article_value" name="article_value">
            <option <?=($gfv->value=="dest_by_leader")?'selected="selected"':''?> value="dest_by_leader">Назначаются лидером этой организации</option>
            <!--<option <?=($gfv->value=="dest_by_stateleader")?'selected="selected"':''?> value="dest_by_stateleader">Назначаются главой государства</option>-->
            <option <?=($gfv->value=="nation_party_vote")?'selected="selected"':''?> value="nation_party_vote">Голосование населения за партии</option>
            <option <?=($gfv->value=="nation_one_party_vote")?'selected="selected"':''?> value="nation_one_party_vote">Голосование населения за членов единственной партии</option>
        </select>
        <?php break;
    case "org_dest_leader":
        ?>
        <select class="bill_field" id="bill_article_value" name="article_value">
            <option <?=($gfv->value=="unlimited")?'selected="selected"':''?> value="unlimited">Назначается предшественником</option>
            <option <?=($gfv->value=="nation_individual_vote")?'selected="selected"':''?> value="nation_individual_vote">Голосование населения за кандидатов</option>
            <option <?=($gfv->value=="nation_party_vote")?'selected="selected"':''?> value="nation_party_vote">Голосование населения за партии</option>
<!--            <option <?=($gfv->value=="other_org_vote")?'selected="selected"':''?> value="other_org_vote">Голосование членов законодательной власти</option>-->
<!--            <option <?=($gfv->value=="dest_by_stateleader")?'selected="selected"':''?> value="dest_by_stateleader">Назначаются главой государства</option>-->
            <option <?=($gfv->value=="org_vote")?'selected="selected"':''?> value="org_vote">Голосование членов этой же организации</option>
        </select>
        <?php break;
    default: ?>
        <input value="<?=$gfv->value?>" type="text" class="bill_field" id="bill_article_value" name="article_value">
        <?php break;
} ?>