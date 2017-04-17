<?php

namespace app\models\population;

use yii\base\Model,
    app\models\Religion,
    app\models\Ideology;

/**
 * 
 * @property Nation $nation
 * @property PopClass $class
 * @property Religion $religion
 * @property Ideology $ideology
 * 
 */
final class PseudoPop extends Model
{
    
    /**
     *
     * @var integer
     */
    public $count;
    
    /**
     *
     * @var integer
     */
    public $tileId;
    
    /**
     *
     * @var integer
     */
    public $nationId;
    
    /**
     *
     * @var integer
     */
    public $classId;
    
    /**
     *
     * @var integer
     */
    public $religionId;
    
    /**
     *
     * @var integer
     */
    public $ideologyId;
    
    /**
     *
     * @var integer
     */
    public $gender;
    
    /**
     *
     * @var integer
     */
    public $age;
    
    public function getNation()
    {
        return Nation::findOne($this->nationId);
    }
    
    public function getClass()
    {
        return PopClass::findOne($this->classId);
    }
    
    public function getReligion()
    {
        return Religion::findOne($this->religionId);
    }
    
    public function getIdeology()
    {
        return Ideology::findOne($this->ideologyId);
    }
    
    /**
     * 
     * @param PseudoPop[] $ar
     * @return PseudoPop[]
     */
    public static function unityIgnoreTile($ar)
    {
        $data = [];
        foreach ($ar as $pop) {
            /* @var $pop PseudoPop */
            $key = $pop->classId.'_'.$pop->nationId.'_'.$pop->ideologyId.'_'.$pop->religionId.'_'.$pop->gender.'_'.$pop->age;
            if (isset($data[$key])) {
                $data[$key]->count += $pop->count;
            } else {
                $data[$key] = $pop;
            }
        }
        
        return array_values($data);
    }
    
}
