<?php

namespace app\components\widgets;

use Yii;

/**
 * 
 */
class BillVotesPieChartWidget extends PieChartWidget
{
    
    private function getName($id)
    {
        $names = [
            Yii::t('app', 'Votes Plus'),
            Yii::t('app', 'Votes Abstain'),
            Yii::t('app', 'Votes Minus'),
        ];
        return $names[$id];
    }
    
    private function getColor($id)
    {
        $colors = ['green', 'gray', 'red'];
        return $colors[$id];
    }
    
    public function init()
    {
        parent::init();
        
        $this->colName = Yii::t('app', 'Variant');
        $this->colors = [];
        $this->table = [];
        foreach (array_keys($this->data) as $variant) {
            $this->colors[] = $this->getColor($variant);
            $this->table[] = [
                'id' => $variant,
                'name' => $this->getName($variant),
                'color' => $this->getColor($variant),
                'percents' => $this->data[$variant],
            ];
        }
    }
}
