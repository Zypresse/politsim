<?php

/*
*	Надстройка над ActiveRecord
*	FindByPk($id) — как в Yii1
*	FindByCode($code) — аналог, для поиска по уникальному полю code
*	getPublicAttributes — получить не все аттрибуты, а только указанные в setPublicAttributes модели
*/

namespace app\components;

use Yii;
use yii\db\ActiveRecord;

abstract class MyModel extends ActiveRecord
{
    /**
     * Поиск по ID
     * 
     * @var integer $id
     * @return static
     */
    public static function findByPk($id)
    {
            return static::find()->where(["id"=>$id])->one();
    }

    public static function findByCode($code)
    {
            return static::find()->where(["code"=>$code])->one();
    }

    // Массив имен аттрибутов, доступных для кого угодно
    // true — доступны все
    public function setPublicAttributes() 
    {
            return true;
    }

    public function getPublicAttributes()
    {
    	if ($this->setPublicAttributes() === true) return $this->attributes;

        $ar = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->setPublicAttributes())) {
                $ar[$key] = $value;
            }
        }
        return $ar;
    }
    
    /**
     * 
     * @param array $params
     * @param boolean $save
     * @param array $paramsToCreate
     * @return \self
     */
    public static function findOrCreate($params, $save = false, $paramsToCreate = [], $paramsToLoad = [])
    {
        $m = static::find()->where($params)->one();
        if (is_null($m)) {
            $m = new static(array_merge($params,$paramsToCreate));
        } else {
            if ($paramsToLoad === false) {
                $paramsToLoad = $paramsToCreate;
            }
            $m->load($paramsToLoad, '');
        }
        if ($save) {
            $m->save();
        }
        
        return $m;
    }
    
    public static function findAll($condition = false)
    {
        if ($condition === false) {
            return static::find()->all();
        } else {
            return parent::findAll($condition);
        }
    }
    
}