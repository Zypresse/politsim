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
}