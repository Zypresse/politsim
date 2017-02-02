<?php

namespace app\models\population;

/**
 * 
 */
final class PopPack
{

    /**
     *
     * @var PopClass
     */
    public $popClass;
    
    /**
     *
     * @var double
     */
    public $count;
    
    public function __construct(float $count, int $popClassId)
    {
        $this->popClass = PopClass::findOne($popClassId);
        $this->count = $count;
    }
    
}
