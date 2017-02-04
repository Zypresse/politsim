<?php

namespace app\models\economics\resources;

use Yii,
    app\models\population\PopClass,
    app\models\economics\resources\base\NoSubtypesResourceProto;

/**
 * 
 * @property PopClass $popClass
 * 
 */
final class Work extends NoSubtypesResourceProto
{
    
    public function getPopClass()
    {
        return PopClass::findOne($this->id);
    }
    
    public function getName()
    {
        return Yii::t('app', 'Work of {0}', [$this->popClass->name]);
    }

    public function getIcon()
    {
        return '<i class="fa fa-cogs" style="color:'.$this->popClass->color.'"></i>';
    }

}
