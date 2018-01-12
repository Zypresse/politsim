<?php

?>
<script type="text/javascript">
    
    function sendElectionRequestModal(id) {
        createAjaxModal(
            'elections/send-request-form',
            {id: id},
            '<?=Yii::t('app', 'Election request')?>',
            '<button class="btn btn-primary send-election-request-confirm-btn" onclick="sendElectionRequestConfirm('+id+')" data-id="'+$(this).data('id')+'"><?=Yii::t('app', 'Send request')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    }
    
    function sendElectionRequestConfirm(id) {
        json_request('elections/send-request', {id: id});
    }
    
    function electionVoteModal(id) {
        createAjaxModal(
            'elections/vote-form',
            {id: id},
            '<?=Yii::t('app', 'Ballot')?>',
            '<button class="btn btn-primary election-vote-confirm-btn" onclick="electionVoteConfirm('+id+')" disabled="disabled" data-id="'+$(this).data('id')+'"><?=Yii::t('app', 'Vote')?></button><button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?=Yii::t('app', 'Close')?></button>'
        );
    }
    
    function electionVoteConfirm(id) {
        json_request('elections/vote', {id: id, variant: $('#election-variant-selected').val()});
    }
    
</script>
