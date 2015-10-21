<?php

namespace app\models\objects\proto;

use yii\db\ActiveQuery;

/**
 * 
 *
 * @author ilya
 */
class ObjectProtoQuery extends ActiveQuery {
    
    public $class_name;

    public function prepare($builder)
    {
        if ($this->class_name !== null) {
            $this->andWhere(['class_name' => $this->class_name]);
        }
        return parent::prepare($builder);
    }
    
}
