<?php

namespace app\controllers;

use yii;
use app\components\MyController;
use app\components\MyHtmlHelper;
use app\models\User;
use app\models\GovermentFieldType;
use app\models\Org;
use app\models\Resurse;
use app\models\Region;
use app\models\BillType;
use app\models\Bill;
use app\models\ElectRequest;
use app\models\Post;
use app\models\State;
use app\models\Party;
use app\models\Dealing;

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
            $user = User::find()->where(["twitter_nickname"=>$nick])->one();
        }
        if (is_null($user)) {
            $this->error = 'User not found';
        } else {
            $this->result = $user->getPublicAttributes();
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
                    $this->result[] = ['code'=>$region->code,$code=>$region->attributes[$code]];
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
            $this->result[] = ['code'=>$region->code,'population'=>$region->population];
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
            if ($user->post->can_make_dicktator_bills) {

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
                $bill->creator = $user->post_id;
                $bill->created = time();
                $bill->vote_ended = time() - 1;
                $bill->state_id = $user->state_id;
                $bill->data = json_encode($data,JSON_UNESCAPED_UNICODE);
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

    public function actionDropElectRequest($org_id,$leader = 0)
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
                    $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>1,'candidat'=>$user->id])->one();
                else
                    $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>1,'party_id'=>$user->party_id])->one();
            } else {
                if (!($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>0,'party_id'=>$user->party_id])->one();
            }

            if (is_null($request))
                return $this->_r("Request not found");

            $request->delete();
            $this->result = "ok";
            return $this->_r();
            
        } else
            return $this->_r("Invalid organisation ID");
    }

    public function actionElectRequest($org_id,$leader = 0,$candidat = 0)
    {
        $org_id = intval($org_id);
        $candidat = intval($candidat) ? intval($candidat) : $this->viewer_id;
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
                    $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>1,'candidat'=>$user->id])->count();
                else
                    $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>1,'party_id'=>$user->party_id])->count();
            } else {
                if (!($user->isPartyLeader()))
                    return $this->_r("Not allowed");

                $request = ElectRequest::find()->where(['org_id'=>$org_id,'leader'=>0,'party_id'=>$user->party_id])->count();
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

    public function actionCreateState($name,$short_name,$goverment_form,$capital,$color,$flag = false)
    {
       
        if ($name && $short_name && $goverment_form && $capital && $color) {

            $flag = ($flag) ? $flag : "http://placehold.it/300x200/eeeeee/000000&text=".urlencode(MyHtmlHelper::transliterate($short_name));

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
                $executive->name = "Правительство ".$short_name;
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
                    $leader->can_make_dicktator_bills = 1;
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

    public function actionGetCitizenship($state_id)
    {
        $state_id = intval($state_id);
        if ($state_id>0) {
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
        if ($party_id>0) {
            $user = User::findByPk($this->viewer_id);
            if ($user->party_id) 
                return $this->_r("You allready have party");
            
            $party = Party::findByPk($party_id);
            if (is_null($party))
                return $this->_r("Party not found");
            if ($user->state_id === $party->state_id)
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

    public function actionTransferMoney($count,$uid,$is_anonim = false,$is_secret = false)
    {
        $count = intval($count);
        $uid = intval($uid);
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

    public function actionCreateParty($name,$short_name,$ideology = 10,$image = false)
    {
        $name = trim(strip_tags($name));
        $short_name = mb_strtoupper(mb_substr(trim(strip_tags($short_name)), 0,6));
        $image = trim(strip_tags($image));
        $image = ($image) ? $image : "http://placehold.it/300x200/eeeeee/000000&text=".urlencode(MyHtmlHelper::transliterate($short_name));
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

            if ($party->save()){
                $user->party_id = $party->id;
                $user->save();
                $this->result = "ok";
            } else
                $this->error = $party->getErrors();
            return $this->_r();

        } else
            return $this->_r("Invalid params");
    }

    public function actionPublicStatement($uid,$type = 'positive')
    {
        $uid = intval($uid);
        if ($uid > 0) {
            $user = User::findByPk($uid);
            if (is_null($user))
                return $this->_r("User not found");
            $self = User::findByPk($this->viewer_id);
            if ($self->last_vote > time() - 24*60*60)
                return $this->_r("timeout",['time'=>($self->last_vote + 24*60*60)]);

            $user->star += round($self->star/10);
            $self->star += 1;
            switch ($type) {
                case 'positive':
                    $user->heart += ($self->heart > 0) ? round($self->heart/10) : round(abs($self->heart/100));
                    $self->heart += 1;
                    $user->chart_pie += ($self->chart_pie > 0) ? 1 : 0;
                break;
                case 'negative':
                    $user->heart += -1 * ($self->heart > 0 ? round($self->heart/10) : round(abs($self->heart/100)));
                    $self->heart += -1;
                    $user->chart_pie += ($self->chart_pie > 0) ? -1 : 0;
                break;
                default:
                    $user->heart += -2 * ($self->heart > 0 ? round($self->heart/10) : round(abs($self->heart/100)));
                    $self->heart += -1 * round(abs($self->heart/10));
                    $user->chart_pie += -1;
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

    public function actionRenameParty($name,$short_name)
    {
        $name = trim(strip_tags($name));
        $short_name = mb_strtoupper(mb_substr(trim(strip_tags($short_name)), 0,6));
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
                $post->type = "minister";
                $post->can_delete = 1;

                if ($post->save()) 
                    return $this->_rOk();
                else
                    return $this->_r($org->getErrors());                
            } else
                return $this->_r("Not allowed");
        } else
            return $this->_r("Invalid name");
    }

    public function actionSetPost($id,$uid)
    {
        $id = intval($id);
        $uid = intval($uid);
        if ($id>0 && $uid>0) {
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
                $old = User::find()->where(['post_id'=>$id])->one();
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
        if ($id>0) {
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
                $old = User::find()->where(['post_id'=>$id])->one();
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
        if ($id>0) {
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
                $old = User::find()->where(['post_id'=>$id])->one();
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

}
