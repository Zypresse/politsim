<?php

namespace app\models\economics\decisions;

use Yii,
    app\components\LinkCreator,
    app\models\Tile,
    app\models\economics\units\BuildingTwotiled,
    app\models\economics\units\BuildingTwotiledProto,
    app\models\economics\CompanyDecision,
    app\models\economics\CompanyDecisionProto;

/**
 * 
 */
final class CreateBuildingTwotiled extends CompanyDecisionProto
{
    
    public function accept(CompanyDecision $decision): bool
    {
        $building = $this->instantiateBuilding($decision);
        return $building->save();
    }

    public function render(CompanyDecision $decision): string
    {
        $protoName = BuildingTwotiledProto::getList()[(int)$decision->dataArray['protoId']];
        $tile = Tile::findByPk($decision->dataArray['tileId']);
        $tile2 = Tile::findByPk($decision->dataArray['tile2Id']);
        return Yii::t('app', 'Create new line «{0}» from {1} to {2}', [
            $protoName,
            $tile->city ? LinkCreator::cityLink($tile->city) : LinkCreator::regionLink($tile->region),
            $tile2->city ? LinkCreator::cityLink($tile2->city) : LinkCreator::regionLink($tile2->region),
        ]);
    }

    public function validate(CompanyDecision $decision): bool
    {
        if (!isset($decision->dataArray['protoId']) || !$decision->dataArray['protoId']) {
            $decision->addError('dataArray[protoId]', Yii::t('app', 'Building type is required field'));
        } else {
            if (!BuildingTwotiledProto::exist($decision->dataArray['protoId'])) {
                $decision->addError('dataArray[stateId]', Yii::t('app', 'Invalid building type'));
            } else {
                $proto = BuildingTwotiledProto::instantiate($decision->dataArray['protoId']);
            }
        }
        if (!isset($decision->dataArray['tileId']) || !$decision->dataArray['tileId']) {
            $decision->addError('dataArray[tileId]', Yii::t('app', 'Tile is required field'));
        } else {
            $tile = Tile::findByPk($decision->dataArray['tileId']);
            if (is_null($tile) || is_null($tile->region) || !$tile->region->stateId) {
                $decision->addError('dataArray[tileId]', Yii::t('app', 'Invalid tile'));
            } elseif (isset($proto)) {
                foreach ($proto->buildLicenses as $licenseProto) {
                    if (!$decision->company->isHaveLicense($licenseProto->id, $tile->region->stateId)) {
                        $decision->addError('dataArray[protoId]', Yii::t('app', 'Company have not required licenses to construct building of selected type in this state'));
                        break;
                    }
                }
            }
        }
        if (!isset($decision->dataArray['tile2Id']) || !$decision->dataArray['tile2Id']) {
            $decision->addError('dataArray[tile2Id]', Yii::t('app', 'Second tile is required field'));
        } else {
            $tile2 = Tile::findByPk($decision->dataArray['tile2Id']);
            if (is_null($tile2) || is_null($tile2->region) || !$tile2->region->stateId) {
                $decision->addError('dataArray[tile2Id]', Yii::t('app', 'Invalid tile'));
            } elseif (isset($proto)) {
                foreach ($proto->buildLicenses as $licenseProto) {
                    if (!$decision->company->isHaveLicense($licenseProto->id, $tile2->region->stateId)) {
                        $decision->addError('dataArray[protoId]', Yii::t('app', 'Company have not required licenses to construct building of selected type in this state'));
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
    
    private function instantiateBuilding(CompanyDecision $decision) : BuildingTwotiled
    {
        return new BuildingTwotiled([
            'masterId' => $decision->company->getUtr(),
            'protoId' => $decision->dataArray['protoId'],
            'tileId' => $decision->dataArray['tileId'],
            'tile2Id' => $decision->dataArray['tile2Id'],
            'name' => $decision->dataArray['name'],
            'nameShort' => $decision->dataArray['nameShort'],
            'size' => $decision->dataArray['size'],
        ]);
    }

}
