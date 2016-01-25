<?php

namespace app\models;

use app\components\TaxPayer,
    app\components\MyModel,
    app\components\MyHtmlHelper,
    app\models\Utr,
    app\models\PopClass,
    app\models\PopNation,
    app\models\Region,
    app\models\Religion,
    app\models\Ideology,
    app\models\factories\Factory,
    app\models\resurses\ResurseCost;

/**
 * Минимальная группа населения. Таблица "population".
 *
 * @property integer $id
 * @property integer $class ID класса населения
 * @property integer $nation ID национальности
 * @property integer $ideology ID идеологии
 * @property integer $religion ID религии
 * @property integer $sex Пол (0 - мужск., 1 - женск.)
 * @property integer $age Возраст
 * @property integer $count Число людей
 * @property integer $region_id ID региона
 * @property integer $factory_id ID региона
 * @property double $contentment Удовлетворённость
 * @property double $consciousness Сознательность
 * @property double $money
 * @property integer $last_salary Дата получения последней зарплаты
 * 
 * @property PopClass $classinfo Класс населения
 * @property PopNation $nationinfo Национальность
 * @property Region $region Регион
 * @property Factory $factory Фабрика
 * @property Ideology $ideologyinfo Идеология
 * @property Religion $religioninfo Религия
 */
class Population extends MyModel implements TaxPayer {

    public function getUnnpType()
    {
        return Utr::TYPE_POP;
    }

    public function getUnnp() {
        if (is_null($this->utr)) {
            $u = Utr::findOneOrCreate(['p_id' => $this->id, 'type' => $this->getUnnpType()]);
            $this->utr = ($u) ? $u->id : 0;
            $this->save();
        } 
        return $this->utr;
    }

    public function isGoverment($stateId)
    {
        return false;
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
            [['region_id', 'factory_id', 'class', 'nation', 'ideology', 'religion', 'sex', 'age', 'count'], 'integer']
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
            'religion' => 'ID религии (0 - нейтрал)',
            'sex' => 'Пол 0 - женский, 1 - мужской',
            'age' => 'Возраст (в игровых годах)',
            'count' => 'Число человек',
        ];
    }

    public function getClassinfo()
    {
        return $this->hasOne(PopClass::className(), array('id' => 'class'));
    }

    public function getNationinfo()
    {
        return $this->hasOne(PopNation::className(), array('id' => 'nation'));
    }

    public function getRegion()
    {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }

    public function getIdeologyinfo()
    {
        return $this->hasOne(Ideology::className(), array('id' => 'ideology'));
    }

    public function getReligioninfo()
    {
        return $this->hasOne(Religion::className(), array('id' => 'religion'));
    }

    public function getFactory()
    {
        return $this->hasOne(Factory::className(), array('id' => 'factory_id'));
    }

    /**
     * Получить все группы
     * @param ActiveRecord $query
     * @return \app\models\Population[]
     */
    public static function getAllGroups($query = false)
    {
        if ($query === false) {
            $query = static::find();
        }
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
        if ($query === false) {
            $query = static::find();
        }

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
                        'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d, true)
                    ];
                }
                if (isset($modelsByClass[$model->class]['religions'][$model->religion])) {
                    $modelsByClass[$model->class]['religions'][$model->religion]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['religions'][$model->religion] = [
                        'name' => $model->religioninfo->name,
                        'count' => $model->count,
                        'color' => '#' . $model->religioninfo->color
                    ];
                }
                if (isset($modelsByClass[$model->class]['nations'][$model->nation])) {
                    $modelsByClass[$model->class]['nations'][$model->nation]['count'] += $model->count;
                } else {
                    $modelsByClass[$model->class]['nations'][$model->nation] = [
                        'name' => $model->nationinfo->name,
                        'count' => $model->count,
                        'color' => '#' . $model->nationinfo->color
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
                        'color' => MyHtmlHelper::getSomeColor($i, static::ageGroup($model->age)['id'] + 20)
                    ];
                }
            } else {
                $modelsByClass[$model->class] = [
                    'name' => $model->classinfo->name,
                    'ideologies' => [$model->ideology => [
                            'name' => $model->ideologyinfo->name,
                            'count' => $model->count,
                            'color' => MyHtmlHelper::getPartyColor($model->ideologyinfo->d, true)
                        ]],
                    'religions' => [$model->religion => [
                            'name' => $model->religioninfo->name,
                            'count' => $model->count,
                            'color' => '#' . $model->religioninfo->color
                        ]],
                    'nations' => [$model->nation => [
                            'name' => $model->nationinfo->name,
                            'count' => $model->count,
                            'color' => '#' . $model->nationinfo->color
                        ]],
                    'sex' => [$model->sex => [
                            'name' => $model->sex ? 'Мужчины' : 'Женщины',
                            'count' => $model->count,
                            'color' => $model->sex ? '#0000ee' : '#ee0000'
                        ]],
                    'age' => [static::ageGroup($model->age)['id'] => [
                            'name' => static::ageGroup($model->age)['name'],
                            'count' => $model->count,
                            'color' => MyHtmlHelper::getSomeColor($i, static::ageGroup($model->age)['id'] + 20)
                        ]],
                    'count' => $model->count
                ];
            }
        }

        foreach ($modelsByClass as $i => $m) {
            foreach ($m['ideologies'] as $j => $id) {
                $modelsByClass[$i]['ideologies'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
            }
            foreach ($m['religions'] as $j => $id) {
                $modelsByClass[$i]['religions'][$j]['percents'] = round(100 * $id['count'] / $m['count']);
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
        $new->attributes = $this->attributes;
        $new->id = null;
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
        return $this->class . '_' . $this->nation . '_' . $this->ideology . '_' . $this->religion . '_' . $this->sex . '_' . $this->age . '_' . $this->region_id . '_' . $this->factory_id;
    }

    public function changeBalance($delta)
    {
        $this->money += $delta;
        $this->save();
    }

    public function getBalance()
    {
        return $this->money;
    }

    public function getHtmlName()
    {
        return $this->classinfo->name." из региона ".$this->region->getHtmlName();
    }

    public function getTaxStateId()
    {
        return $this->region ? $this->region->state_id : 0;
    }

    public function isTaxedInState($stateId)
    {
        if (is_null($this->region)) {
            return false;
        }
        
        return $this->region->state_id === (int)$stateId;
    }

    public function getUserControllerId()
    {
        return 0;
    }

    public function isUserController($userId)
    {
        return false;
    }
    
    public function pushToStorage($proto_id, $count, $quality = 10) 
    {
        return true;
    }
    
    /**
     * 
     * @param ResurseCost $resursesCosts
     * @param double $maxCount
     * @return double сколько было закуплено
     */
    public function autobuy($resursesCosts, $maxCount)
    {
        $purchasedFood = 0;
        foreach ($resursesCosts as $resurseCost) {
            if ($resurseCost->resurse->count >= $maxCount) {
                $buy = $maxCount;
            } else {
                $buy = $resurseCost->resurse->count;
            }
            $dealing = new Dealing([
                'proto_id' => 1,
                'from_unnp' => $resurseCost->resurse->place->unnp,
                'to_unnp' => $this->unnp,
                'sum' => -1*$resurseCost->cost*$buy,
                'items' => json_encode([
                    [
                        'type' => 'resurse',
                        'proto_id' => $resurseCost->resurse->proto_id,
                        'quality' => $resurseCost->resurse->quality,
                        'count' => $buy
                    ]
                ])
            ]);
            if ($dealing->accept()) {
                $purchasedFood += $buy;
            }
            if ($purchasedFood >= $maxCount) {
                break;
            }
        }
        return $purchasedFood;
    }

}
