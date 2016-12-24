<?php

namespace app\models\politics\elections;

use Yii,
    app\models\politics\AgencyPost,
    app\models\population\Pop,
    app\models\politics\constitution\articles\postsonly\DestignationType,
    app\models\politics\constitution\ConstitutionArticleType,
    app\components\MyMathHelper;

/**
 * 
 */
abstract class ElectionManager
{
    
    const DAY_LENGTH = 60*60*24;
    
    /**
     * 
     * @param AgencyPost $post
     * @return Election
     */
    public static function createPostElection(AgencyPost &$post)
    {
        
        /* @var $article DestignationType */
        $destignationTypeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::DESTIGNATION_TYPE);
        $type = (int) $destignationTypeArticle->value;
        $whoId = (int) $destignationTypeArticle->value2;
        $settings = (int) $destignationTypeArticle->value3;
        $termsOfOfficeArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_OFFICE);
        $officeDays = (int) $termsOfOfficeArticle->value;
        $termsOfElectionArticle = $post->constitution->getArticleByType(ConstitutionArticleType::TERMS_OF_ELECTION);
        $registrationDays = (int) $termsOfElectionArticle->value;
        $waitingDays = (int) $termsOfElectionArticle->value2;
        $electionDays = (int) $termsOfElectionArticle->value3;
        
        /* @var $lastElection Election */
        $lastElection = $post->getElections()->orderBy(['id' => SORT_DESC])->one();
        if (is_null($lastElection)) {
            $start = time();
        } else {
            if ($lastElection->status != ElectionStatus::ENDED) {
                return $lastElection;
            }
            $start = ((int) $lastElection->dateVotingEnd) + $officeDays*static::DAY_LENGTH;
        }
        
        $election = new Election([
            'whomType' => ElectionWhomType::POST,
            'whomId' => $post->id,
            'settings' => $settings,
            'dateRegistrationStart' => $start,
            'dateRegistrationEnd' => $start+$registrationDays*static::DAY_LENGTH,
            'dateVotingStart' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH,
            'dateVotingEnd' => $start+$registrationDays*static::DAY_LENGTH+$waitingDays*static::DAY_LENGTH+$electionDays*static::DAY_LENGTH,
        ]);
        
        switch ($type){
            case DestignationType::BY_STATE_ELECTION:
                $election->whoType = ElectionWhoType::STATE;
                $election->whoId = $post->stateId;
                break;
            case DestignationType::BY_DISTRICT_ELECTION:
                $election->whoType = ElectionWhoType::ELECTORAL_DISTRICT;
                $election->whoId = $whoId;
                break;
            case DestignationType::BY_AGENCY_ELECTION:
                $election->whoType = ElectionWhoType::AGENCY_MEMBERS;
                $election->whoId = $whoId;
                break;
            default:
                return null;
        }
        
        $election->save();
        return $election;
    }
    
    
    public static function calculateResults(Election &$election)
    {
        /* @var $post AgencyPost */
        $post = $election->whom;
        $requests = $election->requests;
        $who = $election->who;
        $votesByUsers = $election->votesByUsers;
        $pops = [];
        
        /* @var $pops \app\models\population\Pop[] */
        
        switch ($election->whoType) {
            case ElectionWhoType::STATE:
            case ElectionWhoType::ELECTORAL_DISTRICT: // TODO: add field ElectoralDistrict::$usersFame
                /* @var $who ElectoralDistrict */
                /* @var $who \app\models\politics\State */
                $sumRequestsFame = 0;
                foreach ($requests as $request) {
                    $sumRequestsFame += $request->object->fame;
                }
                $turnout = $who->usersFame > 0 ? $sumRequestsFame/$who->usersFame : 1;
                $tiles = $who->tiles;
                foreach ($tiles as $tile) {
                    $pops = array_merge($pops, $tile->pops);
                }
                break;
            case ElectionWhoType::AGENCY_MEMBERS:
                /* @var $who \app\models\politics\Agency */
                $turnout = count($votesByUsers) / $who->getPosts()->count();
                break;
        }
        
        echo "Turnout: ".$turnout.PHP_EOL;
        echo "NPC count all: ".count($pops).PHP_EOL;
        $results = [];
        foreach ($election->requests as $request) {
            $results[$request->variant] = 0;
        }
        foreach ($votesByUsers as $vote) {
            $results[$vote->variant]++;
        }
        
        $votersCount = round($turnout*count($pops));
        $npcVotes = [];
        if ($votersCount > 0) {
            $popsSelected = array_rand($pops, $votersCount);
            $tmpPops = [];
            foreach ($popsSelected as $i) {
                $tmpPops[] = $pops[$i];
            }
            $pops = $tmpPops;
            unset($tmpPops);
            echo "NPC count voted: ".count($pops).PHP_EOL;
            
            $npcCount = 0;
            foreach ($pops as $pop) {
                $npcCount += $pop->count;
                $pseudoGroup = $pop->getPseudoGroups();
                foreach ($pseudoGroup as $popData) {
                    $variant = static::calcPopVariant($election, $popData);
                    $npcVotes[] = [
                        'electionId' => $election->id,
                        'count' => $popData['count'],
                        'tileId' => $popData['tileId'],
                        'classId' => $popData['classId'],
                        'nationId' => $popData['nationId'],
                        'ideologyId' => $popData['ideologyId'],
                        'religionId' => $popData['religionId'],
                        'age' => $popData['age'],
                        'gender' => $popData['gender'],
                        'districtId' => $pop->tile->electoralDistrictId,
                        'variant' => $variant,
                        'dateCreated' => time()
                    ];
                    $results[$variant] += $popData['count'];
                }
                echo $npcCount.PHP_EOL;
            }
        }
        Yii::$app->db->createCommand()->batchInsert('electionsVotesPops', ['electionId','count','tileId','classId','nationId','ideologyId','religionId','age','gender','districtId','variant','dateCreated'], $npcVotes)->execute();
        
        $election->results = json_encode([
            'turnout' => $turnout,
            'results' => $results
        ]);
        $election->save();

        $max = 0;
        $winnerVariant = 0;
        foreach ($results as $variant => $count) {
            if ($count > $max) {
                $max = $count;
                $winnerVariant = $variant;
            }
        }
        $winner = $election->getRequests()->andWhere(['variant' => $winnerVariant])->one();
        switch ((int) $election->whomType) {
            case ElectionWhomType::POST:
                $election->whom->userId = $winner->objectId;
                $election->whom->save();
                static::createPostElection($election->whom);
                break;
        }
        
    }
    
    /**
     * 
     * @param \app\models\politics\elections\Election $election
     * @param array $popData
     * @return integer
     */
    public static function calcPopVariant(Election &$election, &$popData)
    {
        $potentials = [];
        $sumRating = 0;
        foreach ($election->requests as $request) {
            $rating = static::calcRequestRating($request, $popData)+1;
            $sumRating += $rating;
            $potentials[$request->variant] = $rating;
        }
        foreach ($potentials as $variant => $rating) {
            $potentials[$variant] = $rating/$sumRating;
        }
        return MyMathHelper::randomP($potentials);
    }
    
    /**
     * 
     * @param \app\models\politics\elections\ElectionRequest $request
     * @param array $popData
     * @param double
     */
    public static function calcRequestRating(ElectionRequest &$request, &$popData)
    {
        /* @var $object \app\models\User */
        $object = $request->object;
        $rating = $object->trust + $object->success / 10 + $object->fame / 20;
        return $rating;
    }
    
}
