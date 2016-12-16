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
    public $colors = ["#ff0000", "#ff8000", "#ffff00", "#008000", "#0000ff", "#4b0082", "#9400d3"];
    public $data;
    protected $numbers;
    protected $table;
    protected $colName;

    public function init()
    {
        if (is_string($this->data)) {
            $this->data = json_decode($this->data, true);
        }
        uasort($this->data, function($a, $b) {return $b <=> $a;});
        $this->numbers = implode(',', array_values($this->data));
    }
    
    public function run()
    {
        return $this->render('@app/views/widgets/pie', [
            'width' => $this->width,
            'height' => $this->height,
            'colors' => $this->colors,
            'data' => $this->data,
            'numbers' => $this->numbers,
            'table' => $this->table,
            'colName' => $this->colName,
        ]);
    }
    
}
