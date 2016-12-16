<?php

namespace app\components\widgets;

use yii\base\Widget;
use app\models\politics\State;
use app\models\politics\Region;
use app\models\politics\City;

/**
 * 
 */
class PopInfoMenuWidget extends Widget 
{
    
    const LEVEL_STATE = 0;
    const LEVEL_REGION = 1;
    const LEVEL_CITY = 2;
    
    /**
     *
     * @var yii\db\ActiveQuery
     */
    public $statesQuery;
    
    /**
     *
     * @var State
     */
    public $activeState;
    
    /**
     *
     * @var Region
     */
    public $activeRegion;
    
    /**
     *
     * @var City
     */
    public $activeCity;
    
    /**
     *
     * @var integer
     */
    public $level = 0;
    
    public function init(){
        parent::init();
        if (!$this->statesQuery) {
            $this->statesQuery = State::find();
        }
        if ($this->activeCity) {
            $this->activeRegion = $this->activeCity->region;
            $this->level = static::LEVEL_CITY;
        }
        if ($this->activeRegion) {
            $this->activeState = $this->activeRegion->state;
            if ($this->level < static::LEVEL_REGION) {
                $this->level = static::LEVEL_REGION;
            }
        }
    }
    
    private function getViewFile()
    {
        switch ($this->level) {
            case static::LEVEL_STATE:
                return '@app/views/widgets/popinfomenu/state';
            case static::LEVEL_REGION:
                return '@app/views/widgets/popinfomenu/region';
            case static::LEVEL_CITY:
                return '@app/views/widgets/popinfomenu/city';
        }
    }
    
    public function run()
    {
        return $this->render($this->getViewFile(), [
            'states' => $this->statesQuery->all(),
            'state' => $this->activeState,
            'region' => $this->activeRegion,
            'city' => $this->activeCity,
        ]);
    }
    
}
