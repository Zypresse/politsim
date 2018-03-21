<?php

namespace app\models\base\interfaces;

/**
 *
 * @author ilya
 */
interface MapObject
{
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolygon();
    
}
