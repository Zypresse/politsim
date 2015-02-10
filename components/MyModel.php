<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;

abstract class MyModel extends ActiveRecord
{
	public static function findByPk($id)
	{
		return static::find()->where(["id"=>$id])->one();
	}

	public static function findByCode($code)
	{
		return static::find()->where(["code"=>$code])->one();
	}

	// Массив имен аттрибутов, доступных для кого угодно
	private $publicAttributes = ['id'];

    public function getPublicAttributes()
    {
        $ar = [];
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->publicAttributes)) {
                $ar[$key] = $value;
            }
        }
        return $ar;
    }
}