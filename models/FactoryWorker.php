 <?php

namespace app\models;

/**
 * This is the model class for table "factory_workers".
 *
 * @property integer $id
 * @property integer $factory_id
 * @property integer $pop_id
 * 
 * @property Factory $factory Фабрика
 * @property Population $population Группа населения
 */
class FactoryWorker extends app\components\MyModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'factory_workers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['factory_id', 'pop_id'], 'required'],
            [['factory_id', 'pop_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'factory_id' => 'Factory ID',
            'pop_id' => 'Pop ID',
        ];
    }
    
    
    public function getFactory()
    {
        return $this->hasOne('app\models\Factory', array('id' => 'factory_id'));
    }    
    
    public function getPopulation()
    {
        return $this->hasOne('app\models\Population', array('id' => 'pop_id'));
    }
}
