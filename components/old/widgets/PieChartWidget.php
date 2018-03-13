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
    public $table;
    protected $numbers;
    protected $colName;

    public function init()
    {
        if ($this->data) {
            if (is_string($this->data)) {
                $this->data = json_decode($this->data, true);
            }
            uasort($this->data, function($a, $b) {return $b <=> $a;});
            $this->numbers = implode(',', array_values($this->data));
        } else {
            $this->data = [];
            $this->numbers = '';
        }
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
