<?php

namespace app\models\constitution\proto;

/**
 * Description of JuntaProto
 *
 * @author ilya
 */
abstract class JuntaProto extends AbstractConstitutionProto {
    
    private static $values = [
        1 => 0,
        2 => 'dest_by_leader',
        3 => '',
        4 => 'unlimited',
        5 => '',
        6 => -1,
        7 => -1,
        8 => 1,
        9 => 0,
        10 => 0,
        11 => 1,
        12 => 0,
        13 => 0,
        15 => 1,
        16 => 1,
        17 => 0,
        18 => 100,
        19 => 0,
        20 => 1,
        21 => 0,
        22 => 0,
        23 => 0,
        24 => 0,
        25 => -1,
        26 => -1
    ];


    public static function getArticleValue(ArticleProto $articleProto) {
        return static::$values[$articleProto->id];
    }
    
}
