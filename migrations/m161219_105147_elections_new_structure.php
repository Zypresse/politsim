<?php

use yii\db\Migration;

class m161219_105147_elections_new_structure extends Migration
{
    public function up()
    {
        $this->dropTable('elections');
        $this->createTable('elections', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            // Кого выбираем
            // 1 - конкретный пост
            // 2 - участников организации которые выбираются вместе
            // 3 и т.д. — что-то абстрактное, референдумы и т.п.
            'whomType' => 'UNSIGNED INTEGER(1) NOT NULL',
            // id поста / агенства / референдума / ...
            'whomId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // кто выбирает
            // 1 — всё население страны
            // 2 — всё население избирательного округа
            // 3 и т.д. — члены партии / пятилетние девочки и т.п.
            'whoType' => 'UNSIGNED INTEGER(1) NOT NULL',
            // id страны / округа / неба / аллаха
            'whoId' => 'UNSIGNED INTEGER DEFAULT NULL',
            // bitmask с настройкам
            // — второй тур
            // — .. что-то ещё
            'settings' => 'UNSIGNED INTEGER(4) NOT NULL',
            // связанные выборы (сюда ставится id первого тура у второго напр.)
            'initiatorElectionId' => 'UNSIGNED INTEGER REFERENCES elections(id) DEFAULT NULL',
            'dateRegistrationStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateRegistrationEnd' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingEnd' => 'UNSIGNED INTEGER NOT NULL',
            // общие итоги выборов. по округам и т.д. хранятся отдельно.
            'results' => 'TEXT DEFAULT NULL'
        ]);
        
        $this->dropTable('elections-requests-individual');
        $this->dropTable('elections-requests-party');
        
        $this->createTable('elections-requests', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            // тип заявки
            // 1 — юзер за себя
            // 2 — партия за список
            // 3 и т.д. — вариант на референдуме и т.д.
            'type' => 'UNSIGNED INTEGER(1) NOT NULL',
            // id заявителя / списка / проч.
            'objectId' => 'UNSIGNED INTEGER NOT NULL',
            // порядковый номер заявки
            // выставляется автоматически. можно сделать потом редактирование типа случайного перемешивания
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsRequests', 'elections-requests', ['electionId', 'type', 'objectId'], true);
        $this->createIndex('electionsRequestsVariant', 'elections-requests', ['electionId', 'variant'], true);
        
        $this->dropTable('elections-votes-pops');
        $this->createTable('elections-votes-pops', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'tileId' => 'UNSIGNED INTEGER REFERENCES tiles(id) NOT NULL',
            'classId' => 'UNSIGNED INTEGER(2) NOT NULL',
            'nationId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'ideologyId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'religionId' => 'UNSIGNED INTEGER(4) NOT NULL',
            'gender' => 'UNSIGNED INTEGER(1) NOT NULL',
            'age' => 'UNSIGNED INTEGER(3) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        
        $this->dropTable('elections-votes-users');        
        $this->createTable('elections-votes-users', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsers', 'elections-votes-users', ['electionId', 'userId'], true);

    }

    public function down()
    {
        $this->dropTable('elections');
        // новый обьект выборов должен автоматически создаваться/удаляться при изменениях конституции или завершении предыдущего голосования
        $this->createTable('elections', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            // 1 - в агенство
            // 2 - на пост
            // так же можно будет сделать референдумы напр.
            'protoId' => 'UNSIGNED INTEGER(1) NOT NULL',
            'agencyId'  => 'UNSIGNED INTEGER REFERENCES agencies(id) DEFAULT NULL',            
            'postId'  => 'UNSIGNED INTEGER REFERENCES posts(id) DEFAULT NULL',
            'isIndividual' => 'BOOLEAN NOT NULL',
            'isOnlyParty' => 'BOOLEAN NOT NULL',
            'dateRegistrationStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingStart' => 'UNSIGNED INTEGER NOT NULL',
            'dateVotingEnd' => 'UNSIGNED INTEGER NOT NULL',
            'results' => 'TEXT DEFAULT NULL'
        ]);

        $this->dropTable('elections-requests');
        
        $this->createTable('elections-requests-party', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'partyId' => 'UNSIGNED INTEGER REFERENCES parties(id) NOT NULL',

            'partyListId' => 'UNSIGNED INTEGER REFERENCES `parties-lists`(id) NOT NULL',
            // порядковый номер заявки
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsRequestsParty', 'elections-requests-party', ['electionId', 'partyId'], true);
        $this->createIndex('electionsRequestsPartyVariant', 'elections-requests-party', ['electionId', 'variant'], true);

        $this->createTable('elections-requests-individual', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',

            // порядковый номер заявки
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsRequestsIndividual', 'elections-requests-individual', ['electionId', 'userId'], true);
        $this->createIndex('electionsRequestsIndividualVariant', 'elections-requests-individual', ['electionId', 'variant'], true);

        $this->dropTable('elections-votes-users');
        $this->createTable('elections-votes-users', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'userId' => 'UNSIGNED INTEGER REFERENCES users(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
        $this->createIndex('electionsVotesUsers', 'elections-votes-users', ['electionId', 'userId'], true);

        $this->dropTable('elections-votes-pops');
        $this->createTable('elections-votes-pops', [
            'electionId' => 'UNSIGNED INTEGER REFERENCES elections(id) NOT NULL',
            'districtId' => 'UNSIGNED INTEGER REFERENCES `electoral-districts`(id) NOT NULL',
            
            'count' => 'UNSIGNED INTEGER NOT NULL',
            'popData' => 'TEXT NOT NULL',

            'variant' => 'UNSIGNED INTEGER(3) NOT NULL',
            'dateCreated' => 'UNSIGNED INTEGER NOT NULL'
        ]);
    }
}
