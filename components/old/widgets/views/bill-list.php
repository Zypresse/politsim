<?php 
use app\models\articles\proto\ArticleProto,
    app\models\licenses\proto\LicenseProto,
    app\models\Region,
    app\models\Org,
    app\models\CoreCountry,
    app\components\MyHtmlHelper,
    yii\helpers\Html;
?>
<dl id="<?= $id ?>" style="<?= $style ?>" >
    <?php foreach ($bills as $bill) { ?>
        <dt><?= htmlspecialchars($bill->proto->name) ?> <br><ul>
        <?php         $data = json_decode($bill->data, true);
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                foreach ($bill->proto->fields as $field) {
                    if ($field->system_name === $key) {
                        $name = $field->name;
                        break;
                    }
                }
                ?>
                    <li style="font-size:80%">
                        <?= htmlspecialchars($name) ?> — 
                        <?php                         switch ($key) {
                            case 'cost':
                            case 'cost_noncitizens':
                            case 'money':
                                $value = $value . ' ' . MyHtmlHelper::icon('money');
                                break;
                            case 'is_only_goverment':
                            case 'is_need_confirm':
                            case 'is_need_confirm_noncitizens':
                                $value = ($value ? 'ДА' : 'НЕТ');
                                break;
                            case 'new_capital':
                                $region = Region::findByPk($value);
                                if (is_null($region)) {
                                    break;
                                }
                                $value = $region->city;
                                break;
                            case 'region_id':
                                $region = Region::findByPk($value);
                                if (is_null($region)) {
                                    break;
                                }
                                $value = (in_array($field->type, ['cities_all', 'cities'])) ? $region->city : $region->name;
                                break;
                            case 'new_flag':
                                $value = Html::a($value, $value);
                                break;
                            case 'new_anthem':
                                $value = Html::a($value, $value);
                                break;
                            case 'new_color':
                                $value = "<span style=\"background-color:{$value}\"> &nbsp; </span>";
                                break;
                            case 'article_proto_id':
                                $gft = ArticleProto::findByPk($value);
                                if (is_null($gft)) {
                                    break;
                                }
                                $value = $gft->name;
                                break;
                            case 'license_proto_id':
                                $hlt = LicenseProto::findByPk($value);
                                if (is_null($hlt)) {
                                    break;
                                }
                                $value = $hlt->name;
                                break;
                            case 'org_id':
                                $org = Org::findByPk($value);
                                if (is_null($org)) {
                                    break;
                                }
                                $value = $org->name;
                                break;
                            case 'elected_variant':
                                $value = explode('_', $value);
                                $org = Org::findByPk($value[0]);
                                if (is_null($org)) {
                                    break;
                                }
                                $value = ($value[1]) ? "Выборы на пост «{$org->leader->name}» в организации «{$org->name}»" : "Выборы членов организации «{$org->name}»";
                                break;
                            case 'legislature_type':
                                $value = (intval($value) === 1) ? 'Стандартный парламент (10 мест)' : 'Неизвестно';
                                break;
                            case 'core_id':
                                $core = CoreCountry::findByPk($value);
                                if ($core) {
                                    $value = $core->name;
                                } else {
                                    $value = "Нет";
                                }
                                break;
                            case 'article_value':
                                // var_dump($gft);
                                if ($gft)
                                    switch ($gft->type) {
                                        case 'checkbox':
                                            $value = $value ? 'Да' : 'Нет';
                                            break;
                                        case 'org_dest_leader':
                                        case 'org_dest_members':
                                            $value = [
                                                'nation_individual_vote' => 'голосование населения за кандидатов',
                                                'nation_party_vote' => 'голосование населения за партии',
                                                'other_org_vote' => 'голосование членов другой организации',
                                                'org_vote' => 'голосование членов этой же организации',
                                                'unlimited' => 'пожизненно',
                                                'dest_by_leader' => 'назначаются лидером',
                                                'nation_one_party_vote' => 'голосование населения за членов единственной партии',
                                                    ][$value];
                                            break;
                                    }
                                break;
                            default:
                                $value = htmlspecialchars($value);
                                break;
                        }
                        ?>
                        &laquo;<span class="dynamic_field" data-type="<?= $key ?>"><?= $value ?></span>&raquo;</li>
                <?php }
            } ?>
        </ul></dt>
        <dd><?php if ($bill->creatorUser) { ?>Предложил<?php if (intval($bill->creatorUser->sex) === 1) { ?>а<?php } ?> <a href="#" onclick="load_page('profile', {'uid':<?= $bill->creatorUser->id ?>})"><?= htmlspecialchars($bill->creatorUser->name) ?></a><?php } ?> <span class="formatDate" data-unixtime="<?= $bill->created ?>"><?= date("d-M-Y H:i", $bill->created) ?></span><br>
            <?php if ($bill->accepted) { ?>
                Вступил в силу <span class="formatDate" data-unixtime="<?= $bill->accepted ?>"><?= date("d-M-Y H:i", $bill->accepted) ?></span>
            <?php } else { ?>
                Голосование продлится до <span class="formatDate" data-unixtime="<?= $bill->vote_ended ?>"><?= date("d-M-Y H:i", $bill->vote_ended) ?></span>
            <?php } ?><br>
            <?php             if ($showVoteButtons || !$bill->accepted) {
                $allreadyVoted = false;
                $za = 0;
                $protiv = 0;
                $vozder = 0;
                foreach ($bill->votes as $vote) {
                    if ($user && $vote->post_id === $user->post_id) {
                        $allreadyVoted = $vote;
                    }
                    if ($vote->variant === 1) {
                        $za++;
                    } elseif ($vote->variant === 2) {
                        $protiv++;
                    } else {
                        $vozder++;
                    }
                }
                ?>
                Результаты голосования: <span style="color:green"><?= $za ?> за</span>, <span style="color:red"><?= $protiv ?> против</span>, <?= $vozder ?> воздержались.<br>
                <?php                 if ($user && !(is_null($user->post)) && $user->post->canVoteForBills() && $showVoteButtons && $user->state_id === $bill->state_id) {
                    if (!$allreadyVoted) {
                        ?>
                        <button onclick="voteForBill(<?= $bill->id ?>, 1)" style="color:green">За</button> <button onclick="voteForBill(<?= $bill->id ?>, 2)" style="color:red">Против</button> <button onclick="voteForBill(<?= $bill->id ?>, 0)" style="color:#080808">Воздержаться</button>
                    <?php } else { ?>
                        <?= $allreadyVoted->variant === 1 ? '<span style="color:green">Вы проголосовали ЗА законопроект</span>' : ($allreadyVoted->variant === 2 ? '<span style="color:red">Вы проголосовали ПРОТИВ законопроекта</span>' : 'Вы воздержались от голосования по данному законопроекту'); ?>
                    <?php }
                }
                if ($user && $user->post->canVetoBills()) {
                    ?>
                    <button class="btn btn-danger btn-small" onclick="json_request('veto-bill', {'id':<?= $bill->id ?>})" >Наложить вето</button>
            <?php }
        }
        ?>
        </dd>
<?php } ?>
</dl>