<?php

namespace app\models;

use Yii;
use app\components\MyModel;
use app\components\MyHtmlHelper;

/**
 * This is the model class for table "population".
 *
 * @property integer $id
 * @property integer $class
 * @property integer $nation
 * @property integer $ideology
 * @property integer $sex
 * @property integer $count
 */
class Population extends MyModel
{
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
            [['class', 'nation', 'ideology', 'sex', 'count', 'age', 'region_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Регион',
            'class' => 'ID класса (рабочие, клерки и т.д.)',
            'nation' => 'ID национальности',
            'ideology' => 'ID идеологии (0 - нейтрал)',
            'sex' => 'Пол 0 - женский, 1 - мужской',
            'age' => 'Возраст (в игровых годах)',
            'count' => 'Число человек',
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

    public static function getAllGroups($query = false)
    {
        if ($query === false) $query = static::find();

        return $query->all();
    }

    private static function ageGroup($age)
    {
        switch (true) {
            case $age < 18:
                return ['id'=>0,'name'=>'Младше 18-ти'];            
            case $age < 30:
                return ['id'=>1,'name'=>'18-30 лет'];
            case $age < 65:
                return ['id'=>2,'name'=>'30-65 лет'];
            default:
                return ['id'=>3,'name'=>'Старше 65-ти'];
        }
    }

    public static function getGroupsByClass($query = false)
    {
        if ($query === false) $query = static::find();

        $models = $query->all();
        $modelsByClass = [];


        foreach ($models as $i => $model) {
            if (isset($modelsByClass[$model->class])) {
                $modelsByClass[$model->class]['count'] += $model->count;
                if (isset($modelsByClass[$model->class]['ideologies'][$model->ideology])) {
                    $modelsByClass[$model->class]['ideologies'][$model->ideology]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['ideologies'][$model->ideology] = [
                        'name' => $model->ideologyinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d)
                    ];
                }
                if (isset($modelsByClass[$model->class]['nations'][$model->nation])) {
                    $modelsByClass[$model->class]['nations'][$model->nation]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['nations'][$model->nation] = [
                        'name' => $model->nationinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i,true)
                    ];
                }
                if (isset($modelsByClass[$model->class]['sex'][$model->sex])) {
                    $modelsByClass[$model->class]['sex'][$model->sex]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['sex'][$model->sex] = [
                        'name' => $model->sex ? 'Мужчины' : 'Женщины',
                        'count' => $model->count,
                        'color' => $model->sex ? '#0000ee' : '#ee0000'
                    ];
                }
                if (isset($modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']])) {
                    $modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['age'][static::ageGroup($model->age)['id']] = [
                        'name' => static::ageGroup($model->age)['name'],
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i,static::ageGroup($model->age)['id']+20)
                    ];
                }
            } else {
                $modelsByClass[$model->class] = [
                    'name' => $model->classinfo->name,
                    'ideologies' => [$model->ideology => [
                        'name' => $model->ideologyinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d)
                    ]],
                    'nations' => [$model->nation => [
                        'name' => $model->nationinfo->name,
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i,true)
                    ]],
                    'sex' => [$model->sex => [
                        'name' => $model->sex ? 'Мужчины' : 'Женщины',
                        'count' => $model->count,
                        'color' => $model->sex ? '#0000ee' : '#ee0000'
                    ]],
                    'age' => [static::ageGroup($model->age)['id'] => [
                        'name' => static::ageGroup($model->age)['name'],
                        'count' => $model->count,
                        'color' => MyHtmlHelper::getSomeColor($i,static::ageGroup($model->age)['id']+20)
                    ]],
                    'count' => $model->count
                ];
            }
        }

        foreach ($modelsByClass as $i => $m) {
            foreach ($m['ideologies'] as $j => $id) {
                $modelsByClass[$i]['ideologies'][$j]['percents'] = round(100*$id['count']/$m['count']);
            }
            foreach ($m['nations'] as $j => $id) {
                $modelsByClass[$i]['nations'][$j]['percents'] = round(100*$id['count']/$m['count']);
            }
            foreach ($m['sex'] as $j => $id) {
                $modelsByClass[$i]['sex'][$j]['percents'] = round(100*$id['count']/$m['count']);
            }
            foreach ($m['age'] as $j => $id) {
                $modelsByClass[$i]['age'][$j]['percents'] = round(100*$id['count']/$m['count']);
            }
        }

        return $modelsByClass;
    }
}
