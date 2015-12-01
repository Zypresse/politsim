<?php

namespace app\models\statistics;

use app\components\MyModel;

/**
 * Description of Statistics
 *
 * @author ilya
 */
abstract class Statistics extends MyModel
{
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->timestamp = time();
        }
        return parent::beforeSave($insert);
    }
}
