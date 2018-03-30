<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\government\State;
use app\models\map\Region;
use app\models\map\Tile;
use app\models\map\Polygon;
use app\components\TileCombiner;

/**
 * Description of TestController
 *
 * @author ilya
 */
class TestController extends Controller
{
    
    const RIGHT_KOREA = 702;
    const WRONG_KOREA = 703;
    
    const SEUL = 1000;
    
    public function actionCreateKorea()
    {
        Region::updateAll(['stateId' => null], ['is not', 'stateId', null]);
        State::deleteAll();
        $north = Region::findOne(self::RIGHT_KOREA);
        $south = Region::findOne(self::WRONG_KOREA);
        
        $state = new State([
            'name' => 'Корейская Империя',
            'nameShort' => 'КИ',
            'cityId' => self::SEUL,
            'mapColor' => '990000',
            'population' => $north->population + $south->population,
        ]);
        $state->save();
        
        $north->stateId = $state->id;
        $south->stateId = $state->id;
        $north->save();
        $south->save();
        
        $polygon = new Polygon([
            'ownerType' => Polygon::TYPE_STATE,
            'ownerId' => $state->id,
            'data' => TileCombiner::combine($state->getTiles()),
        ]);
        if (!$polygon->save()) {
            var_dump($polygon->getErrors()); die();
        }
        
        
    }
    
}
