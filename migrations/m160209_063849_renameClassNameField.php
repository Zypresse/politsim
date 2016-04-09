<?php

use yii\db\Migration;

class m160209_063849_renameClassNameField extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE factories_prototypes RENAME TO factories_prototypes_temp_table;");
        
        $this->execute("
            CREATE TABLE factories_prototypes (
                id            INTEGER NOT NULL
                                      PRIMARY KEY AUTOINCREMENT,
                name          TEXT    NOT NULL,
                level         INTEGER NOT NULL,
                system_name   TEXT    NOT NULL,
                category_id   INTEGER NOT NULL,
                can_build_npc INTEGER NOT NULL
                                      DEFAULT 0,
                build_cost    REAL    NOT NULL,
                class         TEXT    NOT NULL
                                      UNIQUE
            );
        ");
        
        $this->execute("
            INSERT INTO factories_prototypes (
                id,
                name,
                level,
                system_name,
                category_id,
                can_build_npc,
                build_cost,
                class
            )
            SELECT id,
                   name,
                   level,
                   system_name,
                   category_id,
                   can_build_npc,
                   build_cost,
                   class_name
                FROM factories_prototypes_temp_table;
        ");
        
        $this->execute("DROP TABLE factories_prototypes_temp_table;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE factories_prototypes RENAME TO factories_prototypes_temp_table;");

        $this->execute("
            CREATE TABLE factories_prototypes (
                id            INTEGER NOT NULL
                                      PRIMARY KEY AUTOINCREMENT,
                name          TEXT    NOT NULL,
                level         INTEGER NOT NULL,
                system_name   TEXT    NOT NULL,
                category_id   INTEGER NOT NULL,
                can_build_npc INTEGER NOT NULL
                                      DEFAULT 0,
                build_cost    REAL    NOT NULL,
                class_name         TEXT    NOT NULL
                                      UNIQUE
            );
        ");

        $this->execute("
            INSERT INTO factories_prototypes (
                id,
                name,
                level,
                system_name,
                category_id,
                can_build_npc,
                build_cost,
                class_name
            )
            SELECT id,
                   name,
                   level,
                   system_name,
                   category_id,
                   can_build_npc,
                   build_cost,
                   class
                FROM factories_prototypes_temp_table;
        ");
        $this->execute("DROP TABLE factories_prototypes_temp_table;");
    }

}
