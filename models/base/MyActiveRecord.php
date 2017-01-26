<?php

/*
 * 	Надстройка над ActiveRecord
 * 	findByPk($id) — как в Yii1
 * 	getPublicAttributes — получить не все аттрибуты, а только указанные в setPublicAttributes модели
 */

namespace app\models\base;

use Yii,
    yii\db\ActiveRecord,
    app\components\LinkHelper;

abstract class MyActiveRecord extends ActiveRecord {

    /**
     * Поиск по ID
     * 
     * @var integer $id
     * @return static
     */
    public static function findByPk($id) {
        return static::find()->where(["id" => $id])->one();
    }

    // Массив имен аттрибутов, доступных для кого угодно
    // true — доступны все
    public function setPublicAttributes() {
        return true;
    }

    public function getPublicAttributes() {
        if ($this->setPublicAttributes() === true) {
            return $this->attributes;
        }

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
    public static function findOrCreate($params, $save = false, $paramsToCreate = [], $paramsToLoad = []) {
        $m = static::find()->where($params)->one();
        if (is_null($m)) {
            $m = new static(array_merge($params, $paramsToCreate));
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

    /**
     * 
     * @param mixed $condition
     * @return static[]
     */
    public static function findAll($condition = false) {
        if ($condition === false) {
            return static::find()->all();
        } else {
            return parent::findAll($condition);
        }
    }

    public function validateAnthem() {
        if (!LinkHelper::isSoundCloudLink($this->anthem)) {
            $this->addError('anthem', Yii::t('app', 'Anthem are not valid SoundCloud link'));
            return false;
        }
        return true;
    }

    public function validateFlag() {
        if (!LinkHelper::isImageLink($this->flag)) {
            $this->addError('flag', Yii::t('app', 'Flag are not valid image link'));
            return false;
        }
        return true;
    }

}
