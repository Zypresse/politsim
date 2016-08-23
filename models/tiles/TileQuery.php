<?php

namespace app\models\tiles;

/**
 * This is the ActiveQuery class for [[Tile]].
 *
 * @see Tile
 */
class TileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}