<?php

namespace app\components\widgets;

use Yii;
use app\models\Religion;

/**
 * 
 */
class ReligionsPieChartWidget extends PieChartWidget
{
    
    public function init()
    {
        parent::init();
        
        $this->colName = Yii::t('app', 'Religion');
        $this->colors = [];
        $this->table = [];
        
        foreach (array_keys($this->data) as $religionId) {
            $religion = Religion::findOne($religionId);
            $this->colors[] = $religion->color;
            $this->table[] = [
                'id' => $religionId,
                'name' => $religion->name,
                'color' => $religion->color,
                'percents' => $this->data[$religionId],
            ];
        }
    }
    
}
