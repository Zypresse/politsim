<?php

namespace app\components\widgets;

use Yii;

/**
 * 
 */
class GendersPieChartWidget extends PieChartWidget
{
    
    private function getName($id)
    {
        $names = [
            Yii::t('app', 'Unknown gender'),
            Yii::t('app', 'Female'),
            Yii::t('app', 'Male'),
        ];
        return $names[$id];
    }
    
    private function getColor($id)
    {
        $colors = ['#999', '#f00', '#00f'];
        return $colors[$id];
    }
    
    public function init()
    {
        parent::init();
        
        $this->colName = Yii::t('app', 'Gender');
        $this->colors = [];
        $this->table = [];
        foreach (array_keys($this->data) as $gender) {
            $this->colors[] = $this->getColor($gender);
            $this->table[] = [
                'id' => $gender,
                'name' => $this->getName($gender),
                'color' => $this->getColor($gender),
                'percents' => $this->data[$gender],
            ];
        }
    }
    
}
