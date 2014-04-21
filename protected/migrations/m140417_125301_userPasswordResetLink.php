<?php

class m140417_125301_userPasswordResetLink extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('{{user}}', 'password_reset_code', 'VARCHAR(64)');
    }

    public function safeDown()
    {
        $this->dropColumn('{{user}}', 'password_reset_code');
    }
}