<?php

namespace app\components\widgets;

use yii\base\Widget;

/**
 * 
 */
class PieChartWidget extends Widget
{
    
    public $width = 100;
    public $height = 100;
    public $colors = '["#ff0000", "#ff8000", "#ffff00", "#008000", "#0000ff", "#4b0082", "#9400d3"]';
    public $data;
    protected $numbers;
    
    public function init()
    {
        if ($this->data) {
            $this->numbers = implode(',', array_values($this->data));
        }
        parent::init();
    }
    
    public function run()
    {
        return $this->render('@app/views/widgets/pie', [
            'width' => $this->width,
            'height' => $this->height,
            'colors' => $this->colors,
            'data' => $this->data,
            'numbers' => $this->numbers,
        ]);
    }
    
}
