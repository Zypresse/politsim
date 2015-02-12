<?php

namespace app\controllers;

use yii;
use app\components\MyController;
use app\models\User;
use app\models\GovermentFieldType;
use app\models\Org;
use app\models\Resurse;
use app\models\Region;
use app\models\BillType;
use app\models\Bill;
use app\models\ElectRequest;

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

}
