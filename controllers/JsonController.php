<?php

namespace app\controllers;

use yii;
use yii\base\ViewContextInterface;
use yii\web\Controller;
use app\models\User;
use app\models\GovermentFieldType;
use app\models\Org;
use app\models\Resurse;
use app\models\Region;

class JsonController extends Controller implements ViewContextInterface
{
    private $result = 'undefined';
    private $error = false;
    private function _r() 
    {
        if ($this->error) $this->result = 'error';
        return $this->render('json',['result'=>$this->result,'error'=>$this->error]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\JsonErrorAction',
            ],
        ];
    }
	public $layout = 'api';
	public function getViewPath()
	{
	    return Yii::getAlias('@app/views');
	}

    public function actionHello()
    {
        $this->result = 'Hello, world!';
        return $this->_r(); 
    }

    public function actionUserinfo($uid = false, $nick = false)
    {
        if ($uid === false && $nick === false) {
            $this->error = 'Invalid params';
        } else {
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
        }
        return $this->_r();
    }

    public function actionGovermentfieldtype_info($id)
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

    public function actionOrg_info($id)
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

    public function actionRegion_info($code)
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

    public function actionRegions_resurses($code)
    {
        if ($code) {
            $resurse = Resurse::findByCode($code);
            if (is_null($resurse)) {
                $this->error = "Resurse not found";
            } else {
                $regions = Region::findAll();
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

    public function actionRegions_population()
    {
    
        $regions = Region::findAll();
        $this->result = [];
        foreach ($regions as $region) {
            $this->result[] = ['code'=>$region->code,'population'=>$region->population];
        }    
        
        return $this->_r();
    }

}
