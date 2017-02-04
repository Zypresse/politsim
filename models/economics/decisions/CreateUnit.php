<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\Tile,
    app\models\economics\units\Unit,
    app\models\economics\units\UnitProto,
    app\models\economics\units\Status,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class CreateUnit extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $building = $this->instantiateBuilding($decision);
        return $building->save();
    }

    public function render(CompanyDecision $decision): string
    {
        $protoName = UnitProto::getList()[(int)$decision->dataArray['protoId']];
        $tile = Tile::findByPk($decision->dataArray['tileId']);
        return Yii::t('app', 'Create new unit «{0}» in {1}', [
            $protoName,
            $tile->city ? LinkCreator::cityLink($tile->city) : LinkCreator::regionLink($tile->region),
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        if (!isset($decision->dataArray['protoId']) || !$decision->dataArray['protoId']) {
            $decision->addError('dataArray[protoId]', Yii::t('app', 'Firm type is required field'));
        } else {
            if (!UnitProto::exist($decision->dataArray['protoId'])) {
                $decision->addError('dataArray[stateId]', Yii::t('app', 'Invalid firm type'));
            }
        }
        if (!isset($decision->dataArray['tileId']) || !$decision->dataArray['tileId']) {
            $decision->addError('dataArray[tileId]', Yii::t('app', 'Tile is required field'));
        } else {
            $tile = Tile::findByPk($decision->dataArray['tileId']);
            if (is_null($tile) || is_null($tile->region) || !$tile->region->stateId) {
                $decision->addError('dataArray[tileId]', Yii::t('app', 'Invalid tile'));
            } else {
                $proto = UnitProto::instantiate($decision->dataArray['protoId']);
                foreach ($proto->buildLicenses as $licenseProto) {
                    if (!$decision->company->isHaveLicense($licenseProto->id, $tile->region->stateId)) {
                        $decision->addError('dataArray[protoId]', Yii::t('app', 'Company have not required licenses to create firm of selected type in this state'));
                        break;
                    }
                }
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
        
        if (!count($decision->getErrors())) {
            $building = $this->instantiateBuilding($decision);
            if (!$building->validate()) {
                foreach ($building->getErrors() as $attr => $errors) {
                    foreach ($errors as $error) {
                        $decision->addError("dataArray[{$attr}]", $error);
                    }
                }
            }
        }
        
        return !count($decision->getErrors());
    }
    
    public function getDefaultData(CompanyDecision $decision)
    {
        return [
            'size' => 1,
        ];
    }
    
    private function instantiateBuilding(CompanyDecision $decision) : Unit
    {
        return new Unit([
            'masterId' => $decision->company->getUtr(),
            'protoId' => $decision->dataArray['protoId'],
            'tileId' => $decision->dataArray['tileId'],
            'name' => $decision->dataArray['name'],
            'nameShort' => $decision->dataArray['nameShort'],
            'size' => $decision->dataArray['size'],
            'statusId' => Status::STOPPED_AUTO,
        ]);
    }

}
