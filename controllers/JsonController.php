<?php

namespace app\controllers;

use yii;
use yii\helpers\ArrayHelper;
use app\components\MyController;
use app\components\MyHtmlHelper;
use app\models\User;
use app\models\GovermentFieldType;
use app\models\Org;
use app\models\Resurse;
use app\models\Region;
use app\models\BillType;
use app\models\Bill;
use app\models\BillVote;
use app\models\ElectRequest;
use app\models\ElectVote;
use app\models\Post;
use app\models\State;
use app\models\Party;
use app\models\Dealing;
use app\models\Twitter;
use app\models\Holding;
use app\models\Stock;
use app\models\HoldingDecision;
use app\models\HoldingDecisionVote;
use app\models\Notification;

class JsonController extends MyController {

    public function actions() {
        return [
            'error' => [
                'class' => 'app\components\JsonErrorAction',
            ],
        ];
    }

    public function actionHello() {
        $this->result = 'Hello, world!';
        return $this->_r();
    }

    public function actionUserinfo($uid = false, $nick = false) {
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
            $this->_r('User not found');
        } 
        
        $this->result = ($uid == $this->viewer_id) ? $user->attributes : $user->getPublicAttributes();
                
        $dealingsCount = $user->getNotAcceptedDealingsCount();
        if ($dealingsCount) {
            $this->result['new_dealings_count'] = $dealingsCount;
        }

        return $this->_r();
    }

    public function actionGovermentFieldTypeInfo($id) {
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

    public function actionOrgInfo($id) {
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

    public function actionRegionInfo($code) {
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

    public function actionRegionsResurses($code) {
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

    public function actionRegionsPopulation() {

        $regions = Region::find()->all();
        $this->result = [];
        foreach ($regions as $region) {
            $this->result[] = ['code' => $region->code, 'population' => $region->population];
        }

        return $this->_r();
    }

    public function actionNewBill($bill_type_id) {
        $bill_type_id = intval($bill_type_id);
        if ($bill_type_id > 0) {
            $bill_type = BillType::findByPk($bill_type_id);
            if (is_null($bill_type))
                return $this->_r("Bill type not found");

            $user = User::findByPk($this->viewer_id);
            if (
                ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills)
                || ($user->isOrgLeader() && $user->post->org->leader_can_create_bills)
                || ($user->post->org->can_create_bills)
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
                $bill->vote_ended = ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills) ? time() - 1 : time()+24*60*60;
                $bill->state_id = $user->state_id;
                $bill->dicktator = ($user->isOrgLeader() && $user->post->org->leader_can_make_dicktator_bills)?1:0;
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

    public function actionDropElectRequest($org_id, $leader = 0) {
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

    public function actionElectRequest($org_id, $leader = 0, $candidat = 0) {
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

    public function actionCreateState($name, $short_name, $goverment_form, $capital, $color, $flag = false) {

        if ($name && $short_name && $goverment_form && $capital && $color) {

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

                $region->state_id = $state->id;
                $region->save();

                $executive = new Org();
                $executive->state_id = $state->id;
                $executive->name = "Правительство " . $short_name;
                $executive->leader_dest = 'unlimited';
                $executive->dest = 'dest_by_leader';
                $executive->leader_can_create_posts = 1;
                if ($executive->save()) {
                    $state->executive = $executive->id;
                    $state->save();

                    $leader = new Post();
                    $leader->org_id = $executive->id;
                    $leader->name = "Президент";
                    $leader->type = "dictator";
                    $leader->can_delete = 0;
                    if ($leader->save()) {
                        $executive->leader_post = $leader->id;
                        $executive->save();

                        $user->state_id = $state->id;
                        $user->post_id = $leader->id;
                        $user->save();

                        $minister1 = new Post();
                        $minister1->org_id = $executive->id;
                        $minister1->name = "Министр обороны";
                        $minister1->type = "military_minister";
                        $minister1->save();
                        $minister2 = new Post();
                        $minister2->org_id = $executive->id;
                        $minister2->name = "Министр промышленности";
                        $minister2->type = "industry_minister";
                        $minister2->save();
                        $minister3 = new Post();
                        $minister3->org_id = $executive->id;
                        $minister3->name = "Министр экономики";
                        $minister3->type = "economy_minister";
                        $minister3->save();

                        $this->result = 'ok';
                        return $this->_r();
                    } else
                        return $this->_r($leader->getErrors());
                } else
                    return $this->_r($executive->getErrors());
            } else
                return $this->_r($state->getErrors());
        } else
            return $this->_r("Invalid params");
    }

    public function actionGetCitizenship($state_id) {
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

    public function actionDropCitizenship() {
        $user = User::findByPk($this->viewer_id);
        $user->leaveState();
        $this->result = "ok";
        return $this->_r();
    }

    public function actionJoinParty($party_id) {
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

    public function actionLeaveParty() {
        $user = User::findByPk($this->viewer_id);
        $user->leaveParty();
        $this->result = "ok";
        return $this->_r();
    }

    public function actionTransferMoney($count, $uid, $is_anonim = false, $is_secret = false, $type = 'open') {
        $count = intval($count);
        $uid = intval($uid);

        if ($type === 'anonym')
            $is_anonim = true;
        if ($type === 'hidden')
            $is_secret = true;

        if ($count && $uid && $uid !== $this->viewer_id) {
            $sender = User::findByPk($this->viewer_id);
            $recipient = User::findByPk($uid);
            if (is_null($recipient))
                return $this->_r("User not found");

            $sender->money -= $count;
            $sender->save();
            $recipient->money += $count;
            $recipient->save();

            $dealing = new Dealing();
            $dealing->from_uid = $sender->id;
            $dealing->to_uid = $recipient->id;
            $dealing->sum = $count;
            $dealing->is_anonim = $is_anonim ? 1 : 0;
            $dealing->is_secret = $is_secret ? 1 : 0;
            $dealing->time = time();
            if ($dealing->save()) {
                $this->result = "ok";
                return $this->_r();
            } else
                return $this->_r($dealing->getErrors());
        } else
            return $this->_r("Invalid params");
    }

    public function actionCreateParty($name, $short_name, $ideology = 10, $image = false) {
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

    public function actionPublicStatement($uid, $type = 'positive') {
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
                    $user->chart_pie += ($self->chart_pie > 0) ? 1 : 0;
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

    public function actionRenameOrg($name) {
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

    public function actionRenameParty($name, $short_name) {
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

    public function actionChangePartyLogo($image) {
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

    public function actionCreatePost($name) {
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
                $post->type = "minister";
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

    public function actionSetPost($id, $uid) {
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

    public function actionDropFromPost($id) {
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

    public function actionDeletePost($id) {
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

    public function actionElectVote($request) {
        $request_id = intval($request);
        if ($request_id > 0) {
            $request = ElectRequest::findByPk($request_id);
            if (is_null($request))
                return $this->_r("Request not found");
            $user = User::findByPk($this->viewer_id);
            if ($user->state_id !== $request->org->state_id)
                return $this->_r("Only citizens can vote");

            $elect_requests_ids = implode(",", ArrayHelper::map(ElectRequest::find()->where(["org_id" => $request->org_id, "leader" => $request->leader])->asArray()->all(), 'id', 'id'));
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
            return $this->_r("Invalid request ID");
    }

    public function actionSelfDropFromPost() {
        $user = User::findByPk($this->viewer_id);
        $user->post_id = 0;
        $user->save();
        return $this->_rOk();
    }

    public function actionMoveTo($id) {
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

    public function actionSetTwitterNickname($nick) {
        $nick = mb_strtolower(trim($nick));

        if ($nick && !(preg_match("[^qwertyuiopadsfghjklzxcvbnm0123456789]", $nick)) && strlen($nick) > 3 && strlen($nick) < 20 && !(in_array($nick, ['admin', 'administrator', 'root', 'moder', 'moderator', 'game', 'politsim']))) {
            $user = User::findByPk($this->viewer_id);
            $user->twitter_nickname = $nick;
            $user->save();

            return $this->_rOk();
        } else
            return $this->_r("Invalid nickname");
    }

    public function actionTweet($text, $type = 0, $uid = 0) {
        $text = nl2br(substr(trim(strip_tags($text)), 0, 280));
        $type = intval($type);
        $uid = intval($uid);

        if ($text) {
            $self = User::findByPk($this->viewer_id);
            if ($self->last_tweet > time() - 1 * 60 * 60)
                return $this->_r("timeout", ['time' => ($self->last_tweet + 1 * 60 * 60 - time())]);

            if ($uid) {
                $user = User::findByPk($uid);
                if (is_null($user))
                    return $this->_r("User not found");
            }

            $retweets = round($self->getTwitterSubscribersCount() / 5) + mt_rand(round(-1 * $self->getTwitterSubscribersCount() / 40), round($self->getTwitterSubscribersCount() / 20));

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
                if ($self->id > 1) $self->last_tweet = time();
                $self->save();

                return $this->_rOk();
            } else {
                return $this->_r($tweet->getErrors());
            }
        } else
            return $this->_r("Invalid text");
    }

    public function actionDeleteTweet($id) {
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

    public function actionRetweet($id) {
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
    
    public function actionPartyReserveSetPost($uid,$post_id) {
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
            
            Notification::send($nuser->id, "Вы назначены на пост «".$post->name."»");
            
            return $this->_rOk();
        } else
            return $this->_r("Invalid fields");
    }
    
    public function actionVoteForBill($bill_id,$variant)
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
    
    public function actionCreateHolding($name)
    {
        $user = $this->getUser();
        if ($user->state && $user->state->allow_register_holdings) {
            if ($user->money>=10000) {
        if ($name) {
            $holding = new Holding();
            $holding->name = trim(strip_tags($name));
            $holding->state_id = $user->state_id;
            $holding->balance = 5000;
            if ($holding->save()) {
                $stock = new Stock();
                $stock->count = 10000;
                $stock->holding_id = $holding->id;
                $stock->user_id = $user->id;
                $stock->save();
                
                $user->money -= 10000;
                $user->save();
                
                $this->result = 'ok';
                return $this->_r();
            } else {
                return $this->_r($holding->getErrors());
            }
        } else
            return $this->_r("Invalid fields");
        } else
            return $this->_r("Недостаточно денег");
        } else
            return $this->_r("Not allowed");
    }
    
    public function actionStocksDealing($holding_id, $count, $cost, $uid) {
        $holding_id = intval($holding_id);
        $count = intval($count);
        $cost = intval($cost);
        $uid = intval($uid);
        
        if ($holding_id && $count && $uid) {
            $accepter = User::findByPk($uid);
            if (is_null($accepter))
                return $this->_r("User not found");
            
            $stock = Stock::find()->where(['holding_id'=>$holding_id,'user_id'=>$this->viewer_id])->one();
            
            if (is_null($stock) || $stock->count < $count)
                return $this->_r("Not allowed");
            
            $dealing = new Dealing();
            $dealing->from_uid = $this->viewer_id;
            $dealing->to_uid = $uid;
            $dealing->sum = -1*$cost;
            $dealing->items = json_encode([['type'=>'stock','count'=>$count,'holding_id'=>$holding_id]]);
            $dealing->time = -1;
            
            if ($dealing->save())
                return $this->_rOk();
            else
                return $this->_r($dealing->getErrors());
            
        } else
            return $this->_r("Invalid fields");
    }
    
    public function actionAcceptDealing($id) {
        $id = intval($id);
        if ($id) {
            $dealing = Dealing::findByPk($id);
            if (is_null($dealing))
                return $this->_r("Dealing not found");
            
            if ($dealing->to_uid !== $this->viewer_id)
                return $this->_r("Not allowed");
            
            if ($dealing->sum < 0 && abs($dealing->sum) > $dealing->recipient->money)
                return $this->_r("У вас недостаточно денег");
            
            if ($dealing->sum > 0 && $dealing->sum > $dealing->sender->money)
                return $this->_r("У отправителя недостаточно денег");
            
            $items = json_decode($dealing->items,true);
            
            foreach ($items as $item) {
                switch ($item['type']) {
                    case "stock":
                        $stock = Stock::find()->where(['holding_id'=>$item['holding_id'],'user_id'=>$dealing->from_uid])->one();
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
                $dealing->recipient->save();
                $dealing->sender->money -= $dealing->sum;
                $dealing->sender->save();
            }
            
            foreach ($items as $item) {
                switch ($item['type']) {
                    case "stock":
                        $stock = Stock::find()->where(['holding_id'=>$item['holding_id'],'user_id'=>$dealing->from_uid])->one();
                        
                        $recStock = Stock::find()->where(['holding_id'=>$item['holding_id'],'user_id'=>$dealing->to_uid])->one();
                        if (is_null($recStock)) {
                            $recStock = new Stock();
                            $recStock->user_id = $dealing->to_uid;
                            $recStock->holding_id = $item['holding_id'];
                            $recStock->count = 0;
                        }
                        
                        $stock->count -= $item['count'];
                        $recStock->count += $item['count'];
                        
                        if ($stock->count>0) {
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
    
    
    public function actionDeclineDealing($id) {
        $id = intval($id);
        if ($id) {
            $dealing = Dealing::findByPk($id);
            if (is_null($dealing))
                return $this->_r("Dealing not found");
            
            if ($dealing->to_uid !== $this->viewer_id)
                return $this->_r("Not allowed");
            
            $dealing->delete();
            
            return $this->_rOk();
            
        } else
            return $this->_r("Invalid ID");
    }
    
    public function actionNewHoldingDecision($holding_id,$type) {
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
                    case 1: // Переименование
                        if (empty(strip_tags($_REQUEST['new_name']))) {
                            return $this->_r("Invalid name");
                        } else {
                            $decision->data = ['new_name'=>strip_tags($_REQUEST['new_name'])];
                        }
                    break;
                    case 2: // Выплата дивидентов
                        if (intval($_REQUEST['sum']) > 0) {
                            $decision->data = ['sum'=>intval($_REQUEST['sum'])];
                        } else {
                            return $this->_r("Invalid sum");
                        }
                    break;
                    case 3: // Получение новой лицензии
                        if (intval($_REQUEST['license_id']) > 0) {
                            $decision->data = ['license_id'=>intval($_REQUEST['license_id'])];
                        } else {
                            return $this->_r("Invalid license ID");
                        }
                    break;
                }
                $decision->data = json_encode($decision->data,JSON_UNESCAPED_UNICODE);
                
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
    
    public function actionVoteForDecision($decision_id,$variant) {
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
    
    public function actionInsertMoneyToHolding($holding_id,$sum) {
        $holding_id = intval($holding_id);
        $sum = intval($sum);
        if ($holding_id && $sum>0) {
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
}
