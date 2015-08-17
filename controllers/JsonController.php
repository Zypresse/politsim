<?php

namespace app\controllers;

use yii\helpers\ArrayHelper,
    app\components\MyController,
    app\components\MyHtmlHelper,
    app\models\User,
    app\models\GovermentFieldType,
    app\models\Org,
    app\models\Resurse,
    app\models\Region,
    app\models\BillType,
    app\models\Bill,
    app\models\BillVote,
    app\models\ElectRequest,
    app\models\ElectVote,
    app\models\Post,
    app\models\State,
    app\models\Party,
    app\models\Dealing,
    app\models\Twitter,
    app\models\Holding,
    app\models\Stock,
    app\models\HoldingDecision,
    app\models\HoldingDecisionVote,
    app\models\Notification,
    app\models\Factory,
    app\models\FactoryWorkersSalary,
    app\models\constitution\ConstitutionFactory;

class JsonController extends MyController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\JsonErrorAction',
            ],
        ];
    }

    public function actionHello()
    {
        $this->result = 'Hello, world!';
        return $this->_r();
    }

    public function actionUserinfo($uid = false, $nick = false)
    {
        if ($uid === false && $nick === false) {
            $uid = $this->viewer_id;
        }

        if ($uid) {
            $uid = intval($uid);
            $user = User::findByPk($uid);
        } else {
            $nick = str_replace("@", "", mb_strtolower($nick));
            $user = User::find()->where(["twitter_nickname" => $nick])->one();
        }
        if (is_null($user)) {
            return $this->_r('User not found');
        }

        $this->result = ($uid == $this->viewer_id) ? $user->attributes : $user->getPublicAttributes();

        $dealingsCount = $user->getNotAcceptedDealingsCount();
        if ($dealingsCount) {
            $this->result['new_dealings_count'] = $dealingsCount;
        }

        return $this->_r();
    }

    public function actionGovermentFieldTypeInfo($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $govermentFieldType = GovermentFieldType::findByPk($id);
            if (is_null($govermentFieldType)) {
                $this->error = "Goverment field type not found";
            } else {
                $this->result = $govermentFieldType->getPublicAttributes();
            }
        } else {
            $this->error = "Invalid ID";
        }
        return $this->_r();
    }

    public function actionOrgInfo($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $org = Org::findByPk($id);
            if (is_null($org)) {
                $this->error = "Organisation not found";
            } else {
                $this->result = $org->getPublicAttributes();
            }
        } else {
            $this->error = "Invalid ID";
        }

        return $this->_r();
    }

    public function actionRegionInfo($code)
    {
        if ($code) {
            $region = Region::findByCode($code);
            if (is_null($region)) {
                $this->error = "Region not found";
            } else {
                $this->result = $region->getPublicAttributes();
            }
        } else {
            $this->error = "Invalid ID";
        }

        return $this->_r();
    }

    public function actionRegionsResurses($code)
    {
        if ($code) {
            $resurse = Resurse::findByCode($code);
            if (is_null($resurse)) {
                $this->error = "Resurse not found";
            } else {
                $regions = Region::find()->all();
                $this->result = [];
                foreach ($regions as $region) {
                    $this->result[] = ['code' => $region->code, $code => $region->attributes[$code]];
                }
            }
        } else {
            $this->error = "Invalid code";
        }

        return $this->_r();
    }

    public function actionRegionsPopulation()
    {

        $regions = Region::find()->all();
        $this->result = [];
        foreach ($regions as $region) {
            $this->result[] = ['code' => $region->code, 'population' => $region->population];
        }

        return $this->_r();
    }

    public function actionNewBill($bill_type_id)
    {
        $bill_type_id = intval($bill_type_id);
        if ($bill_type_id > 0) {
            $bill_type = BillType::findByPk($bill_type_id);
            if (is_null($bill_type))
                return $this->_r("Bill type not found");

            $user = User::findByPk($this->viewer_id);
            if (
                    ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills) || ($user->isOrgLeader() && $user->post->org->leader_can_create_bills) || ($user->post->org->can_create_bills)
            ) {

                // находим в запросе данные нужных полей
                $data = [];
                foreach ($bill_type->fields as $field) {
                    if (isset($_REQUEST[$field->system_name])) {
                        $data[$field->system_name] = strip_tags($_REQUEST[$field->system_name]);
                    } else {
                        return $this->_r("Неправильно заполнены поля");
                    }
                }

                if (isset($data['region_code'])) {
                    $region = Region::findByCode($data['region_code']);
                    if (is_null($region) || $region->state_id !== $user->state_id)
                        return $this->_r("Invalid region code");
                }

                $bill = new Bill();
                $bill->bill_type = $bill_type_id;
                $bill->creator = $user->id;
                $bill->created = time();
                $bill->vote_ended = ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills) ? time() - 1 : time() + 24 * 60 * 60;
                $bill->state_id = $user->state_id;
                $bill->dicktator = ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills) ? 1 : 0;
                $bill->data = json_encode($data, JSON_UNESCAPED_UNICODE);
                if ($bill->save()) {
                    $this->result = "ok";
                } else {
                    $this->error = $bill->getErrors();
                }
                return $this->_r();
            } else
                return $this->_r("Action not allowed");
        } else
            return $this->_r("Invalid bill type ID");
    }

    public function actionDropElectRequest($org_id, $leader = 0)
    {
        $org_id = intval($org_id);
        $leader = intval($leader) ? 1 : 0;

        if ($org_id > 0) {
            $user = User::findByPk($this->viewer_id);
            if ($leader) {
                $org = Org::findByPk($org_id);
                if (is_null($org))
                    return $this->_r("Organisation not found");
                if ($org->leader_dest !== 'nation_individual_vote' && !($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                if ($org->leader_dest === 'nation_individual_vote')
                    $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 1, 'candidat' => $user->id])->one();
                else
                    $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 1, 'party_id' => $user->party_id])->one();
            } else {
                if (!($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 0, 'party_id' => $user->party_id])->one();
            }

            if (is_null($request))
                return $this->_r("Request not found");

            $request->delete();
            $this->result = "ok";
            return $this->_r();
        } else
            return $this->_r("Invalid organisation ID");
    }

    public function actionElectRequest($org_id, $leader = 0, $candidat = 0)
    {
        $org_id = intval($org_id);
        $candidat = intval($candidat) ? intval($candidat) : $this->viewer_id;
        $leader = intval($leader) ? 1 : 0;

        if ($org_id > 0) {
            $org = Org::findByPk($org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");

            $user = User::findByPk($this->viewer_id);
            if ($leader) {

                if ($org->leader_dest !== 'nation_individual_vote' && !($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                if ($org->leader_dest === 'nation_individual_vote')
                    $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 1, 'candidat' => $user->id])->count();
                else
                    $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 1, 'party_id' => $user->party_id])->count();
            } else {
                if (!($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                $request = ElectRequest::find()->where(['org_id' => $org_id, 'leader' => 0, 'party_id' => $user->party_id])->count();
            }

            if ($request)
                return $this->_r("Request allready exists");

            if ($candidat !== $this->viewer_id) {
                $c = User::findByPk($candidat);
                if ($c->party_id !== $user->party_id)
                    return $this->_r("Not allowed");
            }

            $request = new ElectRequest();
            $request->org_id = $org_id;
            if ($leader)
                $request->party_id = (($org->leader_dest === 'nation_party_vote') ? $user->party_id : null);
            else
                $request->party_id = $user->party_id;
            $request->candidat = ($leader) ? $candidat : NULL;
            $request->leader = $leader;

            if ($request->save()) {
                $this->result = 'ok';
            } else {
                $this->error = $request->getErrors();
            }

            return $this->_r();
        } else
            return $this->_r("Invalid organisation ID");
    }

    public function actionCreateState($name, $short_name, $goverment_form, $capital, $color, $flag = false)
    {

        if ($name && $short_name && $goverment_form && $capital && $color) {

            $goverment_form = intval($goverment_form);
            $flag = ($flag) ? $flag : "http://placehold.it/300x200/eeeeee/000000&text=" . urlencode(MyHtmlHelper::transliterate($short_name));

            $user = User::findByPk($this->viewer_id);
            if ($user->state_id)
                return $this->_r("You allready have citizenship");
            $region = Region::findByCode($capital);
            if (is_null($region))
                return $this->_r("Region not found");
            if ($region->state_id)
                return $this->_r("Region claimed by other state");

            $state = new State();
            $state->name = trim(strip_tags($name));
            $state->short_name = trim(strip_tags($short_name));
            $state->capital = $capital;
            $state->color = $color;
            $state->flag = trim(strip_tags($flag));
            $state->state_structure = 1; // унитарная
            $state->goverment_form = $goverment_form;

            if ($state->save()) {

                switch ($goverment_form) {
                    case 4: // Хунта
                        $executive = Org::generate($state, Org::EXECUTIVE_JUNTA);
                        $state->executive = $executive->id;
                        $state->save();
                        
                        ConstitutionFactory::generate('Junta', $state->id);
                        break;
                    case 2: // Президентская республика
                        $executive = Org::generate($state, Org::EXECUTIVE_PRESIDENT);
                        $state->executive = $executive->id;
                        $legislature = Org::generate($state, Org::LEGISLATURE_PARLIAMENT10);
                        $state->legislature = $legislature->id;
                        $state->save();

                        ConstitutionFactory::generate('PresidentRepublic', $state->id);
                        break;
                    case 3: // Парламентская республика
                        $executive = Org::generate($state, Org::EXECUTIVE_PRIMEMINISTER);
                        $state->executive = $executive->id;
                        $legislature = Org::generate($state, Org::LEGISLATURE_PARLIAMENT10);
                        $state->legislature = $legislature->id;
                        $state->save();

                        ConstitutionFactory::generate('ParliamentRepublic', $state->id);
                        break;
                }
                
                $region->state_id = $state->id;
                $region->save();

                $user->post_id = $executive->leader_post;
                $user->state_id = $state->id;
                $user->save();

                return $this->_rOk();
            } else {
                return $this->_r($state->getErrors());
            }
        } else {
            return $this->_r("Invalid params");
        }
    }

    public function actionGetCitizenship($state_id)
    {
        $state_id = intval($state_id);
        if ($state_id > 0) {
            $user = User::findByPk($this->viewer_id);
            if ($user->state_id)
                return $this->_r("You allready have citizenship");

            $state = State::findByPk($state_id);
            if (is_null($state))
                return $this->_r("State not found");

            // тут проверка на открытые границы и т.п.

            $user->state_id = $state_id;
            $user->save();
            $this->result = "ok";
            return $this->_r();
        } else
            return $this->_r("Invalid state ID");
    }

    public function actionDropCitizenship()
    {
        $user = User::findByPk($this->viewer_id);
        $user->leaveState();
        $this->result = "ok";
        return $this->_r();
    }

    public function actionJoinParty($party_id)
    {
        $party_id = intval($party_id);
        if ($party_id > 0) {
            $user = User::findByPk($this->viewer_id);
            if ($user->party_id)
                return $this->_r("You allready have party");

            $party = Party::findByPk($party_id);
            if (is_null($party))
                return $this->_r("Party not found");
            if ($user->state_id !== $party->state_id)
                return $this->_r("You have not citizenship for this party");

            // тут проверка на тип партии и т.п.

            $user->party_id = $party_id;
            $user->save();
            $this->result = "ok";
            return $this->_r();
        } else
            return $this->_r("Invalid party ID");
    }

    public function actionLeaveParty()
    {
        $user = User::findByPk($this->viewer_id);
        $user->leaveParty();
        $this->result = "ok";
        return $this->_r();
    }

    public function actionTransferMoney($count, $uid, $is_anonim = false, $is_secret = false, $type = 'open')
    {
        $count = intval($count);
        $uid = intval($uid);

        if ($type === 'anonym') {
            $is_anonim = true;
        }
        if ($type === 'hidden') {
            $is_secret = true;
        }

        if ($count > 0 && $uid > 0 && $uid !== $this->viewer_id) {
            $sender = $this->getUser();
            if ($sender->money < $count) {
                return $this->_r("Недостаточно денег на счету");
            }
            $recipient = User::findByPk($uid);
            if (is_null($recipient)) {
                return $this->_r("User not found");
            }

            $sender->money -= $count;
            if (!$sender->save()) {
                return $this->_r($sender->getErrors());
            }
            $recipient->money += $count;
            if (!$recipient->save()) {
                return $this->_r($recipient->getErrors());
            }

            $dealing = new Dealing([
                'type_id' => 1, // частная сделка
                'from_unnp' => $sender->unnp,
                'to_unnp' => $recipient->unnp,
                'sum' => $count,
                'is_anonim' => $is_anonim ? 1 : 0,
                'is_secret' => $is_secret ? 1 : 0,
                'time' => time()                
            ]);
            if ($dealing->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($dealing->getErrors());
            }
        } else {
            return $this->_r("Invalid params");
        }
    }

    public function actionCreateParty($name, $short_name, $ideology = 10, $image = false)
    {
        $name = trim(strip_tags($name));
        $short_name = mb_strtoupper(mb_substr(trim(strip_tags($short_name)), 0, 6));
        $image = trim(strip_tags($image));
        $image = ($image) ? $image : "http://placehold.it/300x200/eeeeee/000000&text=" . urlencode(MyHtmlHelper::transliterate($short_name));
        $ideology = intval($ideology);

        if ($name && $short_name && $image && $ideology) {
            $user = User::findByPk($this->viewer_id);

            if (!$user->state_id)
                return $this->_r("You have not citizenship");
            if ($user->party_id)
                return $this->_r("You allready have party");

            $party = new Party();
            $party->name = $name;
            $party->short_name = $short_name;
            $party->image = $image;
            $party->state_id = $user->state_id;
            $party->leader = $user->id;
            $party->ideology = $ideology;

            if ($party->save()) {
                $user->party_id = $party->id;
                $user->save();
                $this->result = "ok";
            } else
                $this->error = $party->getErrors();
            return $this->_r();
        } else
            return $this->_r("Invalid params");
    }

    public function actionPublicStatement($uid, $type = 'positive')
    {
        $uid = intval($uid);
        if ($uid > 0) {
            $user = User::findByPk($uid);
            if (is_null($user))
                return $this->_r("User not found");
            $self = User::findByPk($this->viewer_id);
            if ($self->last_vote > time() - 24 * 60 * 60)
                return $this->_r("timeout", ['time' => ($self->last_vote + 24 * 60 * 60 - time())]);

            $user->star += round($self->star / mt_rand(10, 50));
            $self->star += round(mt_rand(0, 100) / 100);
            switch ($type) {
                case 'positive':
                    $user->heart += ($self->heart > 0) ? round($self->heart / mt_rand(10, 50)) : round(abs($self->heart / mt_rand(100, 500)));
                    $self->heart += round(mt_rand(0, 100) / 100);
                    $user->chart_pie += ($self->chart_pie > 0) ? 1 * round(mt_rand(0, 100) / 100) : 0;
                    break;
                case 'negative':
                    $user->heart += -1 * ($self->heart > 0 ? round($self->heart / 10) : round(abs($self->heart / 100)));
                    $self->heart += -1 * round(mt_rand(0, 100) / 100);
                    $user->chart_pie += ($self->chart_pie > 0) ? -1 * round(mt_rand(0, 100) / 100) : 0;
                    break;
                default:
                    $user->heart += -2 * ($self->heart > 0 ? round($self->heart / 10) : round(abs($self->heart / 100)));
                    $self->heart += -1 * round(abs($self->heart / 10));
                    $user->chart_pie += -1 * round(mt_rand(0, 100) / 100);
                    $self->chart_pie += -1;
                    break;
            }

            $self->last_vote = time();
            $user->save();
            $self->save();

            $this->result = "ok";
            return $this->_r();
        } else
            return $this->_r("Invalid user ID");
    }

    public function actionRenameOrg($name)
    {
        $name = trim(strip_tags($name));
        if ($name) {
            $user = User::findByPk($this->viewer_id);
            if (is_null($user->post))
                return $this->_r("You have not post");
            $org = Org::findByPk($user->post->org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");
            if ($user->isOrgLeader()) {
                $org->name = $name;
                if ($org->save())
                    return $this->_rOk();
                else
                    return $this->_r($org->getErrors());
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid name");
    }

    public function actionRenameParty($name, $short_name)
    {
        $name = trim(strip_tags($name));
        $short_name = mb_strtoupper(mb_substr(trim(strip_tags($short_name)), 0, 6));
        if ($name && $short_name) {
            $user = User::findByPk($this->viewer_id);
            if ($user->party_id && $user->isPartyLeader()) {
                $user->party->name = $name;
                $user->party->short_name = $short_name;

                if ($user->party->save())
                    return $this->_rOk();
                else
                    return $this->_r($user->party->getErrors());
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid params");
    }

    public function actionChangePartyLogo($image)
    {
        $image = trim(strip_tags($image));
        if ($image) {
            $user = User::findByPk($this->viewer_id);
            if ($user->party_id && $user->isPartyLeader()) {
                $user->party->image = $image;

                if ($user->party->save())
                    return $this->_rOk();
                else
                    return $this->_r($user->party->getErrors());
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid image");
    }

    public function actionCreatePost($name)
    {
        $name = trim(strip_tags($name));
        if ($name) {
            $user = User::findByPk($this->viewer_id);
            if (is_null($user->post))
                return $this->_r("You have not post");
            $org = Org::findByPk($user->post->org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");
            if ($user->isOrgLeader() && $user->post->org->leader_can_create_posts) {
                $post = new Post();
                $post->name = $name;
                $post->org_id = $org->id;
                $post->can_delete = 1;

                if ($post->save())
                    return $this->_rOk();
                else
                    return $this->_r($post->getErrors());
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid name");
    }

    public function actionSetPost($id, $uid)
    {
        $id = intval($id);
        $uid = intval($uid);
        if ($id > 0 && $uid > 0) {
            $user = User::findByPk($this->viewer_id);
            if (is_null($user->post))
                return $this->_r("You have not post");
            $org = Org::findByPk($user->post->org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");
            $post = Post::findByPk($id);
            if (is_null($post))
                return $this->_r("Post not found");
            if ($post->org_id !== $org->id || $post->id === $org->leader_post)
                return $this->_r("Not allowed");
            $new = User::findByPk($uid);
            if (is_null($new))
                return $this->_r("User not found");
            if ($new->post_id)
                return $this->_r("User allready have post");
            if ($new->state_id !== $user->state_id)
                return $this->_r("User not have that citizenship");

            if ($user->isOrgLeader() && $user->post->org->dest === 'dest_by_leader') {
                $old = User::find()->where(['post_id' => $id])->one();
                if (!(is_null($old))) {
                    $old->post_id = 0;
                    $old->chart_pie -= 1;
                    $old->save();
                    // TODO: Notification
                }

                $new->post_id = $id;
                $new->save();

                return $this->_rOk();
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid params");
    }

    public function actionDropFromPost($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $user = User::findByPk($this->viewer_id);
            if (is_null($user->post))
                return $this->_r("You have not post");
            $org = Org::findByPk($user->post->org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");
            $post = Post::findByPk($id);
            if (is_null($post))
                return $this->_r("Post not found");
            if ($post->org_id !== $org->id || $post->id === $org->leader_post)
                return $this->_r("Not allowed");

            if ($user->isOrgLeader() && $user->post->org->dest === 'dest_by_leader') {
                $old = User::find()->where(['post_id' => $id])->one();
                if (!(is_null($old))) {
                    $old->post_id = 0;
                    $old->chart_pie -= 1;
                    $old->save();
                    // TODO: Notification
                }

                return $this->_rOk();
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid post ID");
    }

    public function actionDeletePost($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $user = User::findByPk($this->viewer_id);
            if (is_null($user->post))
                return $this->_r("You have not post");
            $org = Org::findByPk($user->post->org_id);
            if (is_null($org))
                return $this->_r("Organisation not found");
            $post = Post::findByPk($id);
            if (is_null($post))
                return $this->_r("Post not found");
            if ($post->org_id !== $org->id || $post->id === $org->leader_post || !$post->can_delete)
                return $this->_r("Not allowed");

            if ($user->isOrgLeader()) {
                $old = User::find()->where(['post_id' => $id])->one();
                if (!(is_null($old))) {
                    $old->post_id = 0;
                    $old->chart_pie -= 1;
                    $old->save();
                    // TODO: Notification
                }
                $post->delete();

                return $this->_rOk();
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid post ID");
    }

    public function actionElectVote($request)
    {
        $request_id = intval($request);
        if ($request_id > 0) {
            $request = ElectRequest::findByPk($request_id);
            if (is_null($request))
                return $this->_r("Request not found");
            $user = User::findByPk($this->viewer_id);
            if ($user->state_id !== $request->org->state_id)
                return $this->_r("Only citizens can vote");

            $elect_requests_ids = implode(",", ArrayHelper::map(ElectRequest::find()->where(["org_id" => $request->org_id, "leader" => $request->leader])->asArray()->all(), 'id', 'id'));
            if (count($elect_requests_ids)) {
                $allready_voted = ElectVote::find()->where("request_id IN ({$elect_requests_ids}) AND uid = {$this->viewer_id}")->count();
                if (intval($allready_voted))
                    return $this->_r("Allready voted");


                $vote = new ElectVote();
                $vote->uid = $user->id;
                $vote->request_id = $request->id;

                if ($vote->save())
                    return $this->_rOk();
                else
                    return $this->_r($vote->getErrors());
            } else
                return $this->_r("Здесь должен быть текст ошибки, но его нет. Вы всё равно не читаете тексты ошибок, так для кого я их буду писать?");
        } else
            return $this->_r("Invalid request ID");
    }

    public function actionSelfDropFromPost()
    {
        $user = User::findByPk($this->viewer_id);
        $user->post_id = 0;
        $user->save();
        return $this->_rOk();
    }

    public function actionMoveTo($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $region = Region::findByPk($id);
            if (is_null($region))
                return $this->_r("Region not found");

            $user = User::findByPk($this->viewer_id);
            $user->region_id = $id;
            $user->save();

            return $this->_rOk();
        } else
            return $this->_r("Invalid region ID");
    }

    public function actionSetTwitterNickname($nick)
    {
        $nick = mb_strtolower(trim($nick));

        if ($nick && !(preg_match("[^qwertyuiopadsfghjklzxcvbnm0123456789]", $nick)) && strlen($nick) > 3 && strlen($nick) < 20 && !(in_array($nick, ['admin', 'administrator', 'root', 'moder', 'moderator', 'game', 'politsim']))) {
            $user = User::findByPk($this->viewer_id);
            $user->twitter_nickname = $nick;
            $user->save();

            return $this->_rOk();
        } else
            return $this->_r("Invalid nickname");
    }

    public function actionTweet($text, $type = 0, $uid = 0)
    {
        $text = nl2br(substr(trim(strip_tags($text)), 0, 280));
        $type = intval($type);
        $uid = intval($uid);

        if ($text) {
            $self = User::findByPk($this->viewer_id);
            if ($self->last_tweet > time() - 1 * 60 * 60) {
                return $this->_r("timeout", ['time' => ($self->last_tweet + 1 * 60 * 60 - time())]);
            }

            if ($uid) {
                $user = User::findByPk($uid);
                if (is_null($user)) {
                    return $this->_r("User not found");
                }
            }

            $subs = $self->getTwitterSubscribersCount();

            $retweets = round($subs / 5) + mt_rand(round(-1 * $subs / 40), round($subs / 20));

            if ($uid !== $this->viewer_id) {
                switch ($type) {
                    case 1:
                        # Положительно
                        if ($uid) {
                            $user->heart += ($self->heart > 0) ? round($self->heart / mt_rand(100, 500)) : round(abs($self->heart) / mt_rand(200, 1000));
                            $user->chart_pie += ($self->chart_pie > 0) ? round(mt_rand(0, 5) / 9) : 0;
                            $user->star += round($self->star / 100);
                        }
                        $self->heart += round(mt_rand(0, 5) / 9);
                        $self->star += round($retweets / 1000);
                        break;
                    case 2:
                        # Отрицательно
                        if ($uid) {
                            $user->heart += -1 * ($self->heart > 0 ? round($self->heart / 10) : round(abs($self->heart) / 100));
                            $user->chart_pie += ($self->chart_pie > 0) ? -1 : 0;
                            $user->star += round($self->star / 10);
                        }
                        $self->heart += -1 * round(mt_rand(0, 5) / 9);
                        $self->star += round($retweets / 1000);
                        break;
                    case 3:
                        # Оскорбительно
                        if ($uid) {
                            $user->heart += -2 * ($self->heart > 0 ? round($self->heart / 10) : round(abs($self->heart) / 100));
                            $user->chart_pie += -1 * round(mt_rand(0, 5) / 9);
                            $user->star += round($self->star / 10);
                        }
                        $self->heart += -1 * abs(ceil($self->heart / 100));
                        $self->chart_pie += -1;
                        $self->star += round($retweets / 1000);
                        break;
                    default:
                        $self->star += round($retweets / 1000);
                        break;
                }
            }

            if ($uid) {
                $user->save();
            }
            $self->save();

            $tweet = new Twitter();
            $tweet->uid = $this->viewer_id;
            $tweet->text = $text;
            $tweet->retweets = $retweets;
            $tweet->date = time();
            if ($tweet->save()) {
                if ($self->id > 1)
                    $self->last_tweet = time();
                $self->save();

                return $this->_rOk();
            } else {
                return $this->_r($tweet->getErrors());
            }
        } else
            return $this->_r("Invalid text");
    }

    public function actionDeleteTweet($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $tweet = Twitter::findByPk($id);
            if (is_null($tweet))
                return $this->_r("Tweet not found");
            if ($tweet->uid === $this->viewer_id) {
                $tweet->delete();
                return $this->_rOk();
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid tweet ID");
    }

    public function actionRetweet($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $self = User::findByPk($this->viewer_id);
            if ($self->last_tweet > time() - 1 * 60 * 60)
                return $this->_r("timeout", ['time' => ($self->last_tweet + 1 * 60 * 60 - time())]);

            $tweet = Twitter::findByPk($id);
            if (is_null($tweet))
                return $this->_r("Tweet not found");
            if ($tweet->uid !== $this->viewer_id) {

                $retweets = round($self->getTwitterSubscribersCount() / 7) + mt_rand(round(-1 * $self->getTwitterSubscribersCount() / 30), round($self->getTwitterSubscribersCount() / 30));
                $tweet->user->heart += ($self->heart > 0) ? round($self->heart / 100) : round(abs($self->heart) / 1000);
                $tweet->user->chart_pie += ($self->chart_pie > $tweet->user->chart_pie) ? 1 : 0;
                $tweet->user->star += round($self->star / 100);
                $tweet->user->save();
                $tweet->retweets += $retweets;
                $tweet->save();

                $retweet = new Twitter();
                $retweet->uid = $this->viewer_id;
                $retweet->text = $tweet->text;
                $retweet->retweets = $tweet->retweets;
                $retweet->date = time();
                $retweet->original = $tweet->original ? $tweet->original : $tweet->uid;
                if ($retweet->save()) {
                    if ($self->id > 1)
                        $self->last_tweet = time();
                    $self->save();
                    return $this->_rOk();
                } else {
                    return $this->_r($retweet->getErrors());
                }
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid tweet ID");
    }

    public function actionPartyReserveSetPost($uid, $post_id)
    {
        $uid = intval($uid);
        $post_id = intval($post_id);
        if ($uid && $post_id) {
            $user = $this->getUser();
            if (!($user->isPartyLeader()))
                return $this->_r("Have not permissions");
            if ($uid === $user->id) {
                $nuser = $user;
            } else {
                $nuser = User::findByPk($uid);
                if (is_null($nuser))
                    return $this->_r("User not found");

                if ($nuser->party_id !== $user->party_id || $nuser->post_id)
                    return $this->_r("Not allowed");
            }
            $post = Post::findByPk($post_id);
            if (is_null($post))
                return $this->_r("Post not found");
            $nuser->post_id = $post->id;
            $nuser->save();

            Notification::send($nuser->id, "Вы назначены на пост «" . $post->name . "»");

            return $this->_rOk();
        } else
            return $this->_r("Invalid fields");
    }

    public function actionVoteForBill($bill_id, $variant)
    {
        $bill_id = intval($bill_id);
        $variant = intval($variant);

        if ($bill_id) {
            $user = $this->getUser();
            if (is_null($user->post))
                return $this->_r("Not allowed");
            if ($user->post->canVoteForBills()) {
                $bill = Bill::findByPk($bill_id);
                if ($bill->state_id === $user->state_id) {
                    $bv = new BillVote();
                    $bv->bill_id = $bill_id;
                    $bv->post_id = $user->post_id;
                    $bv->variant = $variant;
                    $bv->save();

                    $this->result = 'ok';
                    return $this->_r();
                } else
                    return $this->_r("Not allowed");
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid fields");
    }

    public function actionCreateHolding($name,$capital)
    {
        $capital = intval($capital);
        $user = $this->getUser();
        if ($user->region && $user->state_id) {
            $inHome = $user->region->state_id === $user->state_id;
            if (($inHome && $user->state->allow_register_holdings) || (!$inHome && $user->region->state->allow_register_holdings_noncitizens)) {
                $mincap = $inHome?$user->region->state->register_holdings_mincap:$user->region->state->register_holdings_noncitizens_mincap;
                $maxcap = $inHome?$user->region->state->register_holdings_maxcap:$user->region->state->register_holdings_noncitizens_maxcap;
                if ($capital < $mincap || ($maxcap>0 && $capital > $maxcap)) {
                    return $this->_r("Invalid capitalisation");
                }
                $sum = $capital + $inHome?$user->region->state->register_holdings_cost:$user->region->state->register_holdings_noncitizens_cost;
                if ($user->money >= $sum) {
                    if (!(empty($name))) {
                        $holding = new Holding();
                        $holding->name = trim(strip_tags($name));
                        $holding->state_id = $user->region->state_id;
                        $holding->balance = $capital;
                        if ($holding->save()) {
                            $stock = new Stock();
                            $stock->count = 10000;
                            $stock->holding_id = $holding->id;
                            $stock->unnp = $user->unnp;
                            $stock->save();

                            $user->money -= $sum;
                            $user->save();

                            return $this->_rOk();
                        } else {
                            return $this->_r($holding->getErrors());
                        }
                    } else {
                        return $this->_r("Invalid fields");
                    }
                } else {
                    return $this->_r("Недостаточно денег");
                }
            } else {
                return $this->_r("Нельзя создать компанию в этом государстве");
            }
        } else {
            return $this->_r("Not allowed");
        }
    }

    public function actionStocksDealing($holding_id, $count, $cost, $uid)
    {
        $holding_id = intval($holding_id);
        $count = intval($count);
        $cost = abs(intval($cost));
        $uid = intval($uid);

        if ($holding_id > 0 && $count > 0 && $uid > 0) {
            $accepter = User::findByPk($uid);
            if (is_null($accepter)) {
                return $this->_r("User not found");
            }

            $stock = Stock::find()->where(['holding_id' => $holding_id, 'unnp' => $this->getUser()->unnp])->one();

            if (is_null($stock) || $stock->count < $count) {
                return $this->_r("Not allowed");
            }

            $dealing = new Dealing([
                'type_id' => 1, // частная сделка
                'from_unnp' => $this->getUser()->unnp,
                'to_unnp' => $accepter->unnp,
                'sum' => -1 * $cost,
                'items' => json_encode([['type' => 'stock', 'count' => $count, 'holding_id' => $holding_id]]),
                'time' => -1,
            ]);

            if ($dealing->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($dealing->getErrors());
            }
        } else {
            return $this->_r("Invalid fields");
        }
    }

    public function actionAcceptDealing($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $dealing = Dealing::findByPk($id);
            if (is_null($dealing)) {
                return $this->_r("Dealing not found");
            }

            if ($dealing->to_unnp !== $this->getUser()->unnp) {
                return $this->_r("Not allowed");
            }

            if ($dealing->sum < 0 && abs($dealing->sum) > $dealing->recipient->money) {
                return $this->_r("У вас недостаточно денег");
            }

            if ($dealing->sum > 0 && $dealing->sum > $dealing->sender->money) {
                return $this->_r("У отправителя недостаточно денег");
            }

            $items = json_decode($dealing->items, true);

            foreach ($items as $item) {
                switch ($item['type']) {
                    case "stock":
                        $stock = Stock::find()->where(['holding_id' => $item['holding_id'], 'unnp' => $dealing->from_unnp])->one();
                        if (is_null($stock) || $stock->count < $item['count']) {
                            return $this->_r("Отправитель не имеет акций, которые предлагает");
                        }
                        break;
                }
            }

            $dealing->time = time();
            $dealing->save();

            if ($dealing->sum) {
                $dealing->recipient->money += $dealing->sum;
                if (!$dealing->recipient->save()) {
                    return $this->_r($dealing->recipient->getErrors());
                }
                $dealing->sender->money -= $dealing->sum;
                if (!$dealing->sender->save()) {
                    return $this->_r($dealing->sender->getErrors());
                }
            }

            foreach ($items as $item) {
                switch ($item['type']) {
                    case "stock":
                        $stock = Stock::find()->where(['holding_id' => $item['holding_id'], 'unnp' => $dealing->sender->unnp])->one();
                        $recStock = Stock::findOrCreate(['holding_id' => $item['holding_id'], 'unnp' => $dealing->recipient->unnp], false, ['count' => 0]);

                        $stock->count -= $item['count'];
                        $recStock->count += $item['count'];

                        if ($stock->count > 0) {
                            $stock->save();
                        } else {
                            $stock->delete();
                        }
                        $recStock->save();
                        break;
                }
            }

            return $this->_rOk();
        } else
            return $this->_r("Invalid ID");
    }

    public function actionDeclineDealing($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $dealing = Dealing::findByPk($id);
            if (is_null($dealing)) {
                return $this->_r("Dealing not found");
            }

            if ($dealing->to_unnp !== $this->getUser()->unnp) {
                return $this->_r("Not allowed");
            }

            $dealing->delete();

            return $this->_rOk();
        } else {
            return $this->_r("Invalid ID");
        }
    }

    public function actionNewHoldingDecision($holding_id, $type)
    {
        $holding_id = intval($holding_id);
        $type = intval($type);
        if ($holding_id && $type) {
            $holding = Holding::findByPk($holding_id);
            if (is_null($holding))
                return $this->_r("Holding not found");

            $user = $this->getUser();

            if ($user->isShareholder($holding)) {
                $decision = new HoldingDecision();
                $decision->created = time();
                $decision->accepted = 0;
                $decision->holding_id = $holding_id;
                $decision->decision_type = $type;
                switch ($type) {
                    case HoldingDecision::DECISION_CHANGENAME: // Переименование
                        if (empty(strip_tags($_REQUEST['new_name']))) {
                            return $this->_r("Invalid name");
                        } else {
                            $decision->data = ['new_name' => strip_tags($_REQUEST['new_name'])];
                        }
                        break;
                    case HoldingDecision::DECISION_PAYDIVIDENTS: // Выплата дивидентов
                        if (intval($_REQUEST['sum']) > 0) {
                            $decision->data = ['sum' => intval($_REQUEST['sum'])];
                        } else {
                            return $this->_r("Invalid sum");
                        }
                        break;
                    case HoldingDecision::DECISION_GIVELICENSE: // Получение новой лицензии
                        if (intval($_REQUEST['license_id']) > 0 && intval($_REQUEST['state_id']) > 0) {
                            $decision->data = [
                                'license_id' => intval($_REQUEST['license_id']),
                                'state_id' => intval($_REQUEST['state_id'])
                            ];
                        } else {
                            return $this->_r("Invalid fields");
                        }
                        break;

                    case HoldingDecision::DECISION_BUILDFABRIC:
                        if (isset($_REQUEST['name']) && isset($_REQUEST['region_id']) && isset($_REQUEST['factory_type']) && isset($_REQUEST['size'])) {
                            $name = trim(strip_tags($_REQUEST['name']));
                            $region_id = intval($_REQUEST['region_id']);
                            $factory_type = intval($_REQUEST['factory_type']);
                            $size = intval($_REQUEST['size']);

                            $fType = \app\models\FactoryType::findByPk($factory_type);

                            if (is_null($fType)) {
                                return $this->_r("Factory type not found");
                            }
                            if ($size < 1 || $size > 127) {
                                return $this->_r("Invalid size");
                            }
                            if (empty($name)) {
                                $name = $fType->name . ' #' . mt_rand(1, 1000);
                            }

                            $decision->data = [
                                'name' => $name,
                                'region_id' => $region_id,
                                'factory_type' => $factory_type,
                                'size' => $size
                            ];
                        } else {
                            return $this->_r("Invalid fields");
                        }
                        break;
                    case HoldingDecision::DECISION_SETMANAGER:
                        if (isset($_REQUEST['factory_id']) && isset($_REQUEST['uid'])) {
                            $factory_id = intval($_REQUEST['factory_id']);
                            $uid = intval($_REQUEST['uid']);
                            if ($factory_id > 0 && $uid > 0) {
                                
                                $factory = \app\models\Factory::findByPk($factory_id);
                                if ($factory->holding_id == $holding_id) {
                                    
                                    $decision->data = [
                                        'factory_id' => $factory_id,
                                        'uid' => $uid
                                    ];
                                    
                                } else {
                                    return $this->_r("Not allowed");
                                }                                
                            } else {
                                return $this->_r("Invalid fields");
                            }                            
                        } else {
                            return $this->_r("Invalid fields");
                        }
                        break;
                    case HoldingDecision::DECISION_SETMAINOFFICE:
                        if (isset($_REQUEST['factory_id'])) {
                            $factory_id = intval($_REQUEST['factory_id']);
                            if ($factory_id > 0 ) {
                                
                                $factory = \app\models\Factory::findByPk($factory_id);
                                if ($factory->holding_id == $holding_id && $factory->type_id == 4) {
                                    
                                    $decision->data = [
                                        'factory_id' => $factory_id
                                    ];
                                    
                                } else {
                                    return $this->_r("Not allowed");
                                }                                
                            } else {
                                return $this->_r("Invalid fields");
                            }
                        } else {
                            return $this->_r("Invalid fields");
                        }
                        break;
                    case HoldingDecision::DECISION_RENAMEFABRIC:
                        if (isset($_REQUEST['factory_id']) && isset($_REQUEST['new_name'])) {
                            $factory_id = intval($_REQUEST['factory_id']);
                            $new_name = trim(strip_tags($_REQUEST['new_name']));
                            if ($factory_id > 0 && !(empty($new_name))) {
                                
                                $factory = \app\models\Factory::findByPk($factory_id);
                                if ($factory->holding_id == $holding_id) {
                                    
                                    $decision->data = [
                                        'factory_id' => $factory_id,
                                        'new_name' => $new_name
                                    ];
                                    
                                } else {
                                    return $this->_r("Not allowed");
                                }                                
                            } else {
                                return $this->_r("Invalid fields");
                            }
                        } else {
                            return $this->_r("Invalid fields");
                        }
                        break;
                }
                $decision->data = json_encode($decision->data, JSON_UNESCAPED_UNICODE);

                if ($decision->save()) {
                    return $this->_rOk();
                } else {
                    return $this->_r($decision->getErrors());
                }
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid fields");
    }

    public function actionVoteForDecision($decision_id, $variant)
    {
        $decision_id = intval($decision_id);
        $variant = intval($variant);
        if ($decision_id && $variant) {
            $decision = HoldingDecision::findByPk($decision_id);
            if (is_null($decision))
                return $this->_r("Decision not found");

            $user = $this->getUser();
            $stock = $user->getShareholderStock($decision->holding);

            if (!(is_null($stock))) {

                foreach ($decision->votes as $vote) {
                    if ($vote->stock_id === $stock->id) {
                        return $this->_r("Allready voted");
                    }
                }

                $vote = new HoldingDecisionVote();
                $vote->decision_id = $decision_id;
                $vote->stock_id = $stock->id;
                $vote->variant = $variant;
                $vote->save();

                return $this->_rOk();
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid fields");
    }

    public function actionInsertMoneyToHolding($holding_id, $sum)
    {
        $holding_id = intval($holding_id);
        $sum = intval($sum);
        if ($holding_id && $sum > 0) {
            $holding = Holding::findByPk($holding_id);
            if (is_null($holding))
                return $this->_r("Holding not found");

            $user = $this->getUser();

            if ($user->isShareholder($holding)) {
                $stock = $user->getShareholderStock($holding);
                switch (get_class($stock->master)) {
                    case 'app\models\User':
                        if ($user->money < $sum)
                            return $this->_r("У вас недостаточно денег");
                        $user->money -= $sum;
                        $user->save();
                        break;
                    case 'app\models\Post':
                    case 'app\models\Holding':
                        if ($stock->master->balance < $sum)
                            return $this->_r("У держателя акций недостаточно денег");
                        $stock->master->balance -= $sum;
                        $stock->master->save();
                        break;
                }
                $holding->balance += $sum;
                $holding->save();

                return $this->_rOk();
            } else {
                return $this->_r("Not allowed");
            }
        } else {
            return $this->_r("Invalid fields");
        }
    }

    public function actionPartyElectSpeakerRequest($uid, $org_id)
    {
        $uid = intval($uid);
        $org_id = intval($org_id);

        if ($uid > 0 && $org_id > 0) {

            $me = $this->getUser();
            if (!$me->isPartyLeader()) {
                return $this->_r("Not allowed. Error #1");
            }

            $user = User::findByPk($uid);
            if (is_null($user)) {
                return $this->_r("User not found");
            }

            if ($user->party_id !== $me->party_id) {
                return $this->_r("Not allowed. Error #2");
            }

            $org = Org::findByPk($org_id);
            if (is_null($org)) {
                return $this->_r("Org not found");
            }

            if ($org->leader_dest !== Org::DEST_ORG_VOTE) {
                return $this->_r("Not allowed. Error #3");
            }

            $request = new \app\models\ElectOrgLeaderRequest();
            $request->org_id = $org_id;
            $request->party_id = $me->party_id;
            $request->uid = $uid;
            if ($request->save()) {
                return $this->_rOk();
            } else {
                return $this->_r($request->getErrors());
            }
        } else {
            return $this->_r("Invalid fields");
        }
    }

    public function actionVoteAboutOrgLeader($request_id)
    {
        $request_id = intval($request_id);

        if ($request_id) {
            $req = \app\models\ElectOrgLeaderRequest::findByPk($request_id);
            if (is_null($req)) {
                return $this->_r("Request not found");
            }

            $user = $this->getUser();

            if (is_null($user->post) || is_null($user->post->org) || $user->post->org->leader_dest !== Org::DEST_ORG_VOTE || $user->isOrgLeader()) {
                return $this->_r("Not allowed");
            }

            if ($user->post->org->isAllreadySpeakerVoted($user->post_id)) {
                return $this->_r("Allready voted");
            }

            $vote = new \app\models\ElectOrgLeaderVote();
            $vote->post_id = $user->post_id;
            $vote->request_id = $request_id;
            $vote->save();

            return $this->_rOk();
        } else {
            return $this->_r("Invalid request ID");
        }
    }

    public function actionVetoBill($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $bill = Bill::findByPk($id);

            if (is_null($bill)) {
                return $this->_r("Bill not found");
            }

            if ($this->getUser()->post && $this->getUser()->post->canVetoBills()) {
                $bill->end();
                return $this->_rOk();
            } else {
                return $this->_r("Not allowed");
            }
        } else {
            return $this->_r("Invalid bill ID");
        }
    }

    public function actionDropLegislature()
    {
        if ($this->getUser()->isStateLeader() && $this->getUser()->state->leader_can_drop_legislature) {
            foreach ($this->getUser()->state->legislatureOrg->posts as $post) {
                if ($post->user) {
                    $post->unlink('user', $post->user);
                }
                if ($post->party_reserve) {
                    $post->party_reserve = 0;
                    $post->save();
                }
            }

            if ($this->getUser()->state->legislatureOrg->next_elect > time() + 2 * 24 * 60 * 60) {
                $this->getUser()->state->legislatureOrg->next_elect = time() + 2 * 24 * 60 * 60;
                $this->getUser()->state->legislatureOrg->save();
            }
            return $this->_rOk();
        } else {
            return $this->_r("Not allowed");
        }
    }

    function actionFactoryManagerSalariesSave($factory_id)
    {
        if (intval($factory_id) > 0) {
            $factory = Factory::findByPk($factory_id);
            if (is_null($factory)) {
                return $this->_r("Factory not found");
            }
            
            if ($factory->manager_uid && $this->viewer_id == $factory->manager_uid) {
                
                foreach ($factory->type->workers as $tWorker) {
                    if (!(isset($_REQUEST['salary_'.$tWorker->pop_class_id]))) {
                        return $this->_r("Invalid fields 1");
                    }
                    $new_salary_value = abs(floatval($_REQUEST['salary_'.$tWorker->pop_class_id]));
                    
                    $saved = false;
                    foreach ($factory->salaries as $salary) {
                        if ($salary->pop_class_id == $tWorker->pop_class_id) {
                            $salary->salary = $new_salary_value;
                            $saved = $salary->save();
                            break;
                        }
                    }
                    if (!$saved) {
                        $salary = new FactoryWorkersSalary();
                        $salary->factory_id = $factory_id;
                        $salary->pop_class_id = $tWorker->pop_class_id;
                        $salary->salary = $new_salary_value;
                        $saved = $salary->save();
                    }
                    
                    if ($saved) {
                        $vacansy = \app\models\Vacansy::find()->where(['factory_id'=>$factory_id,'pop_class_id'=>$tWorker->pop_class_id])->one();
                        if ($vacansy) {
                            $vacansy->salary = $new_salary_value;
                            $vacansy->save();
                        }
                    }
                    
                    if (!$saved) {
                        return isset($salary) ? $this->_r($salary->getErrors()) : 'Undefined error';
                    }
                }
                
                return $this->_rOk();
                
            } else {
                return $this->_r("Not allowed");
            }            
        } else {
            return $this->_r("Invalid factory ID");
        }
    }
    
}
