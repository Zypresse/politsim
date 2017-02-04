<?php

namespace app\models\economics\units;

use Yii,
    app\models\base\ObjectWithFixedPrototypes;

/**
 * 
 */
class Status extends ObjectWithFixedPrototypes
{
    
    public $id;
    public $name;
    public $icon;
    
    const NOT_BUILDED = 0;
    const ACTIVE = 1;
    const CONSTRUCTION = 2;
    const CONSTRUCTION_PAUSE = 3;
    const STOPPED_BY_MANAGER = 4;
    const STOPPED_AUTO = 5;
    
    protected static function getList()
    {
        return [
            [
                'id' => static::NOT_BUILDED,
                'name' => '<span class="badge bg-light-blue">&nbsp;<i class="fa fa-question"></i> '.Yii::t('app', 'Not builded').'&nbsp;</span>',
                'icon' => '<span class="badge bg-light-blue">&nbsp;<i class="fa fa-question" title="'.Yii::t('app', 'Not builded').'"></i>&nbsp;</span>',
            ],
            [
                'id' => static::ACTIVE,
                'name' => '<span class="badge bg-green">&nbsp;<i class="fa fa-play"></i> '.Yii::t('app', 'Active').'&nbsp;</span>',
                'icon' => '<span class="badge bg-green">&nbsp;<i class="fa fa-play" title="'.Yii::t('app', 'Active').'"></i>&nbsp;</span>',
            ],
            [
                'id' => static::CONSTRUCTION,
                'name' => '<span class="badge bg-light-blue">&nbsp;<i class="fa fa-spinner"></i> '.Yii::t('app', 'In construction').'&nbsp;</span>',
                'icon' => '<span class="badge bg-light-blue">&nbsp;<i class="fa fa-spinner" title="'.Yii::t('app', 'In construction').'"></i>&nbsp;</span>',
            ],
            [
                'id' => static::CONSTRUCTION_PAUSE,
                'name' => '<span class="badge bg-red">&nbsp;<i class="fa fa-stop"></i> '.Yii::t('app', 'Construction stopped').'&nbsp;</span>',
                'icon' => '<span class="badge bg-red">&nbsp;<i class="fa fa-stop" title="'.Yii::t('app', 'Construction stopped').'"></i>&nbsp;</span>',
            ],
            [
                'id' => static::STOPPED_BY_MANAGER,
                'name' => '<span class="badge bg-orange">&nbsp;<i class="fa fa-pause"></i> '.Yii::t('app', 'Stopped by manager').'&nbsp;</span>',
                'icon' => '<span class="badge bg-orange">&nbsp;<i class="fa fa-pause" title="'.Yii::t('app', 'Stopped by manager').'"></i>&nbsp;</span>',
            ],
            [
                'id' => static::STOPPED_AUTO,
                'name' => '<span class="badge bg-red">&nbsp;<i class="fa fa-stop"></i> '.Yii::t('app', 'Autostopped').'&nbsp;</span>',
                'icon' => '<span class="badge bg-red">&nbsp;<i class="fa fa-stop" title="'.Yii::t('app', 'Autostopped').'"></i>&nbsp;</span>',
            ],
        ];
    }

}
