<?php

/* @var $election \app\models\politics\elections\Election */
/* @var $viewer \app\models\User */

$data = json_decode($election->results);
$data->results = (array) $data->results;

$maxVotes = 0;
$sumVotes = 0;
foreach ($data->results as $variant => $votes) {
    if ($votes > $maxVotes) {
        $winner = $variant;
        $maxVotes = $votes;
    }
    $sumVotes += $votes;
}
$winner = app\models\politics\elections\ElectionRequest::find()->where(['electionId' => $election->id, 'variant' => $winner])->one();

?>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <p><strong><?=Yii::t('app', 'Current status:')?></strong> <?=Yii::t('app', 'ended')?></p>
        <p><strong><?=Yii::t('app', 'Voting finish:')?></strong> <span class="formatDate" data-unixtime="<?=$election->dateVotingEnd?>"><?=date('d-m-y', $election->dateVotingEnd)?></span></p>
    </div>
    <div class="col-md-6 col-sm-12">
        <pre><?=print_r($winner)?></pre>
    </div>
</div>