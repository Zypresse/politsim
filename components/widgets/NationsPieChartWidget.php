<?php

namespace app\components\widgets;

use Yii;
use app\models\Nation;

/**
 * 
 */
class NationsPieChartWidget extends PieChartWidget
{
    
    public function init()
    {
        parent::init();
        
        $this->colName = Yii::t('app', 'Nation');
        $this->colors = [];
        $this->table = [];
        $i = 0;
        foreach (array_keys($this->data) as $nationId) {
            $nation = Nation::findOne($nationId);
            $this->colors[] = $nation->color;
            $this->table[] = [
                'id' => $nationId,
                'name' => $nation->name,
                'color' => $nation->color,
                'percents' => $this->data[$nationId],
            ];
            
            $i++;
            if ($i > 4) {
//                break;
            }
        }
    }
    
}
