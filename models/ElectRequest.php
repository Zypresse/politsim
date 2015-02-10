<?php

namespace app\models;

use Yii;
use app\components\MyModel;

/**
 * This is the model class for table "elect_requests".
 *
 * @property integer $id
 * @property integer $org_id
 * @property integer $party_id
 * @property integer $candidat
 * @property integer $leader
 */
class ElectRequest extends MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elect_requests';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id'], 'required'],
            [['org_id', 'party_id', 'candidat', 'leader'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'party_id' => 'Party ID',
            'candidat' => 'Candidat',
            'leader' => '1 — заявка на выборы лидера, 0 — на выборы в организаицю',
        ];
    }
}
