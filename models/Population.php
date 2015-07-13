<?php

namespace app\models;

use app\components\NalogPayer,
    app\components\MyHtmlHelper,
    app\models\Unnp;

/**
 * Минимальная группа населения. Таблица "population".
 *
 * @property integer $id
 * @property integer $class ID класса населения
 * @property integer $nation ID национальности
 * @property integer $ideology ID идеологии
 * @property integer $sex Пол (0 - неопр., 1 - женский, 2 - мужской)
 * @property integer $age Возраст
 * @property integer $count Число людей
 * @property integer $region_id ID региона
 * @property integer $factory_id ID региона
 * 
 * @property \app\models\PopClass $classinfo Класс населения
 * @property \app\models\PopNation $nationinfo Национальность
 * @property \app\models\Region $region Регион
 * @property \app\models\Factory $factory Фабрика
 * @property \app\models\Ideology $ideologyinfo Идеология
 * @property Factory $factory Фабрика, на которой они работают
 */
class Population extends NalogPayer
{

    protected function getUnnpType()
    {
        return Unnp::TYPE_POP;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'population';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'required'],
            [['region_id', 'factory_id', 'class', 'nation', 'ideology', 'sex', 'age', 'count'], 'integer']            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'region_id' => 'Регион',
            'class'     => 'ID класса (рабочие, клерки и т.д.)',
            'nation'    => 'ID национальности',
            'ideology'  => 'ID идеологии (0 - нейтрал)',
            'sex'       => 'Пол 0 - женский, 1 - мужской',
            'age'       => 'Возраст (в игровых годах)',
            'count'     => 'Число человек',
        ];
    }

    public function getClassinfo()
    {
        return $this->hasOne('app\models\PopClass', array('id' => 'class'));
    }

    public function getNationinfo()
    {
        return $this->hasOne('app\models\PopNation', array('id' => 'nation'));
    }

    public function getRegion()
    {
        return $this->hasOne('app\models\Region', array('id' => 'region_id'));
    }

    public function getIdeologyinfo()
    {
        return $this->hasOne('app\models\Ideology', array('id' => 'ideology'));
    }

    public function getFactory()
    {
        return $this->hasOne('app\models\Factory', array('id' => 'factory_id'));
    }


    /**
     * Получить все группы
     * @param ActiveRecord $query
     * @return \app\models\Population[]
     */
    public static function getAllGroups($query = false)
    {
        if ($query === false)
            $query = static::find();

        return $query->all();
    }

    /**
     * Название и id возрастной группы
     * @param integer $age
     * @return array
     */
    private static function ageGroup($age)
    {
        switch (true) {
            case $age < 18:
                return ['id' => 0, 'name' => 'Младше 18-ти'];
            case $age < 30:
                return ['id' => 1, 'name' => '18-30 лет'];
            case $age < 65:
                return ['id' => 2, 'name' => '30-65 лет'];
            default:
                return ['id' => 3, 'name' => 'Старше 65-ти'];
        }
    }

    /**
     * Получить группы, сгруппированные по классам
     * @param ActiveRecord $query
     * @return array
     */
    public static function getGroupsByClass($query = false)
    {
        if ($query === false)
            $query = static::find();

        $models        = $query->all();
        $modelsByClass = [];


        foreach ($models as $i => $model) {
            if (isset($modelsByClass[$model->class])) {
                $modelsByClass[$model->class]['count'] += $model->count;
                if (isset($modelsByClass[$model->class]['ideologies'][$model->ideology])) {
                    $modelsByClass[$model->class]['ideologies'][$model->ideology]['count'] += $model->count;
                }
                else {
                    $modelsByClass[$model->class]['ideologies'][$model->ideology] = [
                        'name'  => $model->ideologyinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d, true)
                    ];
                }
                if (isset($modelsByClass[$model->class]['nations'][$model->nation])) {
                    $modelsByClass[$model->class]['nations'][$model->nation]['count'] += $model->count;
                }
                else {
                    $modelsByClass[$model->class]['nations'][$model->nation] = [
                        'name'  => $model->nationinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i, true)
                    ];
                }
                if (isset($modelsByClass[$model->class]['sex'][$model->sex])) {
                    $modelsByClass[$model->class]['sex'][$model->sex]['count'] += $model->count;
                }
                else {
                    $modelsByClass[$model->class]['sex'][$model->sex] = [
                        'name'  => $model->sex ? 'Мужчины' : 'Женщины',
                        'count' => $model->count,
                        'color' => $model->sex ? '#0000ee' : '#ee0000'
                    ];
                }
                if (isset($modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']])) {
                    $modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']]['count'] += $model->count;
                }
                else {
                    $modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']] = [
                        'name'  => static::ageGroup($model->age)['name'],
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i, static::ageGroup($model->age)['id'] + 20)
                    ];
                }
            }
            else {
                $modelsByClass[$model->class] = [
                    'name'       => $model->classinfo->name,
                    'ideologies' => [$model->ideology => [
                            'name'  => $model->ideologyinfo->name,
                            'count' => $model->count,
                            'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d, true)
                        ]],
                    'nations'    => [$model->nation => [
                            'name'  => $model->nationinfo->name,
                            'count' => $model->count,
                            'color' => MyHtmlHelper::getSomeColor($i, true)
                        ]],
                    'sex'        => [$model->sex => [
                            'name'  => $model->sex ? 'Мужчины' : 'Женщины',
                            'count' => $model->count,
                            'color' => $model->sex ? '#0000ee' : '#ee0000'
                        ]],
                    'age'        => [static::ageGroup($model->age)['id'] => [
                            'name'  => static::ageGroup($model->age)['name'],
                            'count' => $model->count,
                            'color' => MyHtmlHelper::getSomeColor($i, static::ageGroup($model->age)['id'] + 20)
                        ]],
                    'count'      => $model->count
                ];
            }
        }

        foreach ($modelsByClass as $i => $m) {
            foreach ($m['ideologies'] as $j => $id) {
                $modelsByClass[$i]['ideologies'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
            }
            foreach ($m['nations'] as $j => $id) {
                $modelsByClass[$i]['nations'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
            }
            foreach ($m['sex'] as $j => $id) {
                $modelsByClass[$i]['sex'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
            }
            foreach ($m['age'] as $j => $id) {
                $modelsByClass[$i]['age'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
            }
        }

        return $modelsByClass;
    }

    /**
     * Выделяет кусок из группы населения 
     * @param int $size размер новой группы
     * @return \self
     */
    public function slice($size)
    {
        
        $new = new self;
        $new->class = $this->class;
        $new->nation = $this->nation;
        $new->ideology = $this->ideology;
        $new->sex = $this->sex;
        $new->age = $this->age;
        $new->region_id = $this->region_id;
        $new->count = $size;
        if ($new->save()) {
            $this->count -= $size;
            $this->save();
            return $new;
        } else {
            return null;
        }
        
    }
    
    public function getUniqueKey()
    {
        return $this->class . '_' . $this->nation . '_' . $this->ideology . '_' . $this->sex . '_' . $this->age . '_' . $this->region_id . '_' . $this->factory_id;
    }
}
