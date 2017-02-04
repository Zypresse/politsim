<?php

namespace app\models\politics;

use Yii,
    app\models\politics\Agency,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * 
 */
class AgencyTemplate extends ObjectWithFixedPrototypes
{
    
    public $id;
    public $name;
    public $class;
    
    const BASIC_PARLIAMENT = 1;
    
    const EMPTY_AGENCY = 2;
    
    protected static function getList()
    {
        return [
            [
                'id' => static::BASIC_PARLIAMENT,
                'name' => Yii::t('app', 'Basic parliament'),
                'class' => 'BasicParliament',
            ],
            [
                'id' => static::EMPTY_AGENCY,
                'name' => Yii::t('app', 'Empty agency'),
                'class' => 'EmptyAgency',
            ],
        ];
    }
    
    /**
     * 
     * @param integer $stateId
     * @return Agency
     */
    public function create(int $stateId, $params)
    {
        $className = '\\app\\models\\politics\\templates\\'.$this->class;
        return $className::create($stateId, $params);
    }

}
