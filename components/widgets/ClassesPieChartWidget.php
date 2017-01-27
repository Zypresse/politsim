<?php

namespace app\components\widgets;

use Yii;
use app\models\population\PopClass;

/**
 * 
 */
class ClassesPieChartWidget extends PieChartWidget
{
    
    public function init()
    {
        parent::init();
        
        $this->colName = Yii::t('app', 'Class');
        $this->colors = [];
        $this->table = [];
        
        foreach (array_keys($this->data) as $classId) {
            $popClass = PopClass::findOne($classId);
            $this->colors[] = $popClass->color;
            $this->table[] = [
                'id' => $classId,
                'name' => $popClass->name,
                'color' => $popClass->color,
                'percents' => $this->data[$classId],
            ];
        }
    }
    
}
