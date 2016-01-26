<?php

namespace app\models;

use app\components\MyModel;

/**
 * Класс населения (напр. рабочие). Таблица "pop_classes".
 *
 * @property integer $id
 * @property string $name Название
 * @property double $food_min_count Минимальное необходимое количество еды
 * @property double $food_max_count Максимальное необходимое количество еды
 * @property double $dress_min_count Минимальное необходимое количество одежды
 * @property double $dress_max_count Максимальное необходимое количество одежды
 * @property double $furniture_min_count Минимальное необходимое количество мебели
 * @property double $furniture_max_count Максимальное необходимое количество мебели
 * @property double $alcohol_min_count Минимальное необходимое количество алкоголя и табака
 * @property double $alcohol_max_count Максимальное необходимое количество алкоголя и табака
 * @property double $energy_min @todo ???
 * @property double $energy_max @todo ???
 * @property double $base_speed Базовая (идеальная) скорость обучения этого типа из безработных
 * @property string $work_type Тип работы @todo ???
 * @property double $awareness Сознательность @todo ???
 * @property double $threshold Порог неудовлетворённости @todo ???
 * @property double $aggression Агрессивность (0-1)
 */
class PopClass extends MyModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pop_classes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'food_min_count', 'food_min_type', 'food_max_count', 'food_max_type', 'dress_min_count', 'dress_min_type', 'dress_max_count', 'dress_max_type', 'furniture_min_count', 'furniture_min_type', 'furniture_max_count', 'furniture_max_type', 'alcohol_min_type', 'alcohol_min_count', 'alcohol_max_type', 'alcohol_max_count', 'energy_min', 'energy_max', 'work_type'], 'required'],
            [['food_min_count', 'food_min_type', 'food_max_count', 'food_max_type', 'dress_min_count', 'dress_min_type', 'dress_max_count', 'dress_max_type', 'furniture_min_count', 'furniture_min_type', 'furniture_max_count', 'furniture_max_type', 'alcohol_min_type', 'alcohol_min_count', 'alcohol_max_type', 'alcohol_max_count', 'energy_min', 'energy_max', 'base_speed'], 'number'],
            [['awareness', 'threshold', 'aggression'], 'integer'],
            [['name', 'work_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID',
            'name'                => 'Name',
            'food_min_count'      => 'Food Min Count',
            'food_min_type'       => 'Food Min Type',
            'food_max_count'      => 'Food Max Count',
            'food_max_type'       => 'Food Max Type',
            'dress_min_count'     => 'Dress Min Count',
            'dress_min_type'      => 'Dress Min Type',
            'dress_max_count'     => 'Dress Max Count',
            'dress_max_type'      => 'Dress Max Type',
            'furniture_min_count' => 'Furniture Min Count',
            'furniture_min_type'  => 'Furniture Min Type',
            'furniture_max_count' => 'Furniture Max Count',
            'furniture_max_type'  => 'Furniture Max Type',
            'alcohol_min_type'    => 'Alcohol Min Type',
            'alcohol_min_count'   => 'Alcohol Min Count',
            'alcohol_max_type'    => 'Alcohol Max Type',
            'alcohol_max_count'   => 'Alcohol Max Count',
            'energy_min'          => 'Energy Min',
            'energy_max'          => 'Energy Max',
            'base_speed'          => 'Base Speed',
            'work_type'           => 'Work Type',
            'awareness'           => 'Awareness',
            'threshold'           => 'Threshold',
            'aggression'          => 'Aggression',
        ];
    }

}
