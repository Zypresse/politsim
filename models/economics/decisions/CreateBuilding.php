<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\Tile,
    app\models\economics\units\Building,
    app\models\economics\units\BuildingProto,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class CreateBuilding extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $building = new Building([
            'masterId' => $decision->company->getUtr(),
            'protoId' => $decision->dataArray['protoId'],
            'tileId' => $decision->dataArray['tileId'],
            'name' => $decision->dataArray['name'],
            'nameShort' => $decision->dataArray['nameShort'],
            'size' => $decision->dataArray['size'],
        ]);
        
        return $building->save();
    }

    public function render(CompanyDecision $decision): string
    {
        $protoName = BuildingProto::getList()[(int)$decision->dataArray['protoId']];
        $tile = Tile::findByPk($decision->dataArray['tileId']);
        return Yii::t('app', 'Create new building «{0}» in {1}', [
            $protoName,
            $tile->city ? LinkCreator::cityLink($tile->city) : LinkCreator::regionLink($tile->region),
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        if (!isset($decision->dataArray['protoId']) || !$decision->dataArray['protoId']) {
            $decision->addError('dataArray[protoId]', Yii::t('app', 'Building type is required field'));
        } else {
            if (!BuildingProto::exist($decision->dataArray['protoId'])) {
                $decision->addError('dataArray[stateId]', Yii::t('app', 'Invalid building type'));
            }
        }
        if (!isset($decision->dataArray['tileId']) || !$decision->dataArray['tileId']) {
            $decision->addError('dataArray[tileId]', Yii::t('app', 'Tile is required field'));
        } else {
            if (!Tile::find()->where(['id' => $decision->dataArray['tileId']])->exists()) {
                $decision->addError('dataArray[tileId]', Yii::t('app', 'Invalid tile'));
            }
        }
        if (!isset($decision->dataArray['name']) || !$decision->dataArray['name']) {
            $decision->addError('dataArray[name]', Yii::t('app', 'Building name is required field'));
        }
        if (!isset($decision->dataArray['nameShort']) || !$decision->dataArray['nameShort']) {
            $decision->addError('dataArray[nameShort]', Yii::t('app', 'Building short name is required field'));
        }
        if (!isset($decision->dataArray['size']) || !$decision->dataArray['size']) {
            $decision->addError('dataArray[size]', Yii::t('app', 'Building size is required field'));
        }
        
        return !count($decision->getErrors());
    }

}
