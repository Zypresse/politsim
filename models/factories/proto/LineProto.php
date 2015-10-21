<?php

namespace app\models\factories\proto;

use app\models\objects\proto\UnmovableObjectProto,
    app\models\resurses\Resurse;

/**
 * This is the model class for table "line_prototypes".
 *
 * @property integer $id
 * @property integer $resurse_proto_id
 * @property double $build_cost
 * @property string $class_name
 */
class LineProto extends UnmovableObjectProto
{
    
    public static function instantiate($row)
    {
        $className = "app\\models\\factories\\proto\\types\\{$row['class_name']}";
        return new $className;
    }
    
    public function getName()
    {
        throw new \yii\base\Exception("Name not defined");
    }
    
    public function getBuildCost()
    {
        return $this->build_cost;
    }
        
    public function getProto()
    {
        return $this->hasOne(Resurse::className(), array('id' => 'resurse_proto_id'));
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'line_prototypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resurse_proto_id', 'build_cost', 'class_name'], 'required'],
            [['resurse_proto_id'], 'integer'],
            [['build_cost'], 'number'],
            [['class_name'], 'string'],
            [['class_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resurse_proto_id' => 'Resurse Proto ID',
            'class_name' => 'Class Name', 
        ];
    }
}