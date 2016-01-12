<?php

namespace app\models;

use app\components\MyModel,
    app\models\Org;

/**
 * Результат выборов. Таблица "elects_results".
 *
 * @property integer $id
 * @property integer $org_id
 * @property integer $leader
 * @property integer $date
 * @property string $data Данные в JSON
 * 
 * @property Org $org
 */
class ElectResult extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elects_results';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['org_id', 'date', 'data'], 'required'],
            [['org_id', 'leader', 'date'], 'integer'],
            [['data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'     => 'ID',
            'org_id' => 'Org ID',
            'leader' => 'Leader',
            'date'   => 'Date',
            'data'   => 'Data',
        ];
    }

    public function getOrg()
    {
        return $this->hasOne(Org::className(), array('id' => 'org_id'));
    }

}
