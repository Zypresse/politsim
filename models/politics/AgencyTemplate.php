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
    
    protected static function getList()
    {
        return [
            [
                'id' => static::BASIC_PARLIAMENT,
                'name' => Yii::t('app', 'Basic parliament'),
                'class' => 'BasicParliament',
            ],
        ];
    }
    
    /**
     * 
     * @param integer $stateId
     * @return Agency
     */
    public function create(int $stateId)
    {
        $className = '\\app\\models\\politics\\templates\\'.$this->class;
        return $className::create($stateId);
    }

}
