<?php

class m140406_182149_initial extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        /**** User *****/
        $this->createTable('{{user}}', array(
            'id'                => 'pk',
            'created_at'        => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'username'          => 'nvarchar(50) NOT NULL',
            'password'          => 'nvarchar(64) NULL',
            'email'             => 'nvarchar(50) NOT NULL',
            'name'              => 'nvarchar(100) NOT NULL',
            'is_active'         => 'tinyint(1) DEFAULT 0 NOT NULL',
            'verification_code' => 'nvarchar(64) NULL',
        ));
    }

    public function safeDown() {
        $this->dropTable('{{user}}');
    }
}