<?php

namespace app\components\widgets;

use app\models\Nation;

/**
 * 
 */
class NationsPieChartWidget extends PieChartWidget
{
    
    public function init()
    {
        uasort($this->data, function($a, $b) {return $b <=> $a;});
        $this->colors = [];
        foreach (array_keys($this->data) as $nationId)
        {
            $nation = Nation::findOne($nationId);
            $this->colors[] = $nation->color;
        }
        $this->colors = json_encode($this->colors);
        parent::init();
    }
    
}
