<?php

namespace app\models\base;

use Yii;
use yii\db\ActiveRecord as YiiActiveRecord;

/**
 * Custom active record
 */
class ActiveRecord extends YiiActiveRecord
{

    /**
     * Finds or creates a model object
     * @param array $params Parameters to find
     * @param boolean $save Flag for save model before return
     * @param array $paramsToCreate Parameters to load in new model
     * @param array|boolean $paramsToLoad Parameters to load in existed model, `true` for use `$paramsToCreate`
     * @return self
     */
    public static function findOrCreate($params, $save = false, $paramsToCreate = [], $paramsToLoad = [])
    {

	$model = static::find()->where($params)->one();
	if (is_null($model)) {
	    $model = new static(array_merge($params, $paramsToCreate));
	} else {
	    if ($paramsToLoad === true) {
		$paramsToLoad = $paramsToCreate;
	    }
	    $model->load($paramsToLoad, '');
	}
	if ($save) {
	    $model->save();
	}

	return $model;
    }

    /**
     * Finds all models by default
     * @param mixed $condition Parameters to find
     * @return static[]
     */
    public static function findAll($condition = false)
    {
	return $condition === false ? static::find()->all() : parent::findAll($condition);
    }

}
