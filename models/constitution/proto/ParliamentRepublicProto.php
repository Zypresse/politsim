<?php

namespace app\models\constitution\proto;

/**
 * Description of ParliamentRepublicProto
 *
 * @author ilya
 */
abstract class ParliamentRepublicProto extends AbstractConstitutionProto {
    
    private static $values = [
        1 => 1,
        2 => 'dest_by_leader',
        3 => 'nation_party_vote',
        4 => 'nation_party_vote',
        5 => 'org_vote',
        6 => 30,
        7 => 30,
        8 => 0,
        9 => 0,
        10 => 0,
        11 => 0,
        12 => 1,
        13 => 1,
        15 => 0,
        16 => 1,
        17 => 0,
        18 => 100,
        19 => 1,
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
