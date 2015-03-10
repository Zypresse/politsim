<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\models\HoldingLicense;
use app\models\StateLicense;

/**
 * This is the model class for table "holding_decisions".
 *
 * @property integer $id
 * @property integer $decision_type
 * @property integer $created
 * @property integer $accepted
 * @property string $data
 * @property integer $holding_id
 */
class HoldingDecision extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'holding_decisions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['decision_type', 'created', 'accepted', 'data', 'holding_id'], 'required'],
            [['decision_type', 'created', 'accepted', 'holding_id'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'decision_type' => 'Decision Type',
            'created' => 'Created',
            'accepted' => 'Accepted',
            'data' => 'Data',
            'holding_id' => 'Holding ID',
        ];
    }
    
    public function getVotes()
    {
        return $this->hasMany('app\models\HoldingDecisionVote', array('decision_id' => 'id'));        
    }
    public function getHolding()
    {
        return $this->hasOne('app\models\Holding', array('id' => 'holding_id'));
    }
    
    public function afterDelete()
    {
        foreach ($this->votes as $vote) {
            $vote->delete();
        }
    }
    
    public function accept()
    {
        $data = json_decode($this->data);
        switch ($this->decision_type) {
            case 1: // смена названия холдинга
                $this->holding->name = $data->new_name;
                $this->holding->save();
            break;
            case 2: // выплата дивидентов
                if ($this->holding->balance >= $data->sum) {
                foreach ($this->holding->stocks as $stock) {
                    $sum = $data->sum*$stock->getPercents()/100;
                    switch (get_class($stock->master)) {
                        case 'app\models\User':
                            $stock->master->money+=$sum;
                        break;
                        case 'app\models\Post':
                            $stock->master->balance+=$sum;
                        break;
                        case 'app\models\Holding':
                            $stock->master->balance+=$sum;
                        break;
                    }
                    $stock->master->save();
                }
                $this->holding->balance -= $data->sum;
                $this->holding->save();
                }
            break;
            case 3: // Получение лицензии
                $stateLicense = StateLicense::find()->where(['state_id'=>$this->holding->state_id,'license_id'=>$data->license_id])->one();
                $allow = true;
                if (!(is_null($stateLicense))) {
                    
                    if ($stateLicense->is_only_goverment) {
                        $allow = false;
                        foreach ($this->holding->stocks as $stock) {
                            if ($stock->post_id) {
                                $allow = true;
                                break;
                            }
                        }
                    }
                    if ($stateLicense->cost) {
                        if ($this->holding->balance<$stateLicense->cost) {
                            $allow = false;
                        }
                    }
                }
                    if ($allow) {
                        $hl = new HoldingLicense();
                        $hl->holding_id = $this->holding_id;
                        $hl->license_id = $data->license_id;
                        $hl->save();
                        
                        if ($stateLicense && $stateLicense->cost) {
                            $this->holding->balance -= $stateLicense->cost;
                            $this->holding->save();
                        }
                    }
                
            break;
        }
        
        $this->delete();
    }
}