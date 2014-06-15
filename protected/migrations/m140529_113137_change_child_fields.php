<?php

class m140529_113137_change_child_fields extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->renameColumn('{{child}}', 'name', 'first_name');
        $this->addColumn('{{child}}', 'middle_name', 'varchar(100) NOT NULL');
        $this->addColumn('{{child}}', 'last_name', 'varchar(100) NOT NULL');

        $this->addColumn('{{child}}', 'state', 'varchar(30) NOT NULL');
        $this->addColumn('{{child}}', 'school_state', 'varchar(30) NOT NULL');

        $this->addColumn('{{child}}', 'grade', 'integer NOT NULL');

        $this->addColumn('{{child}}', 'city', 'varchar(100) NOT NULL');
        $this->addColumn('{{child}}', 'school_city', 'varchar(100) NOT NULL');



        $this->dropColumn('{{child}}', 'known_relatives');
	}

	public function safeDown()
	{
        $this->renameColumn('{{child}}', 'first_name', 'name');
        $this->dropColumn('{{child}}', 'middle_name');
        $this->dropColumn('{{child}}', 'last_name');
        $this->dropColumn('{{child}}', 'state');
        $this->dropColumn('{{child}}', 'school_state');

        $this->dropColumn('{{child}}', 'grade');

        $this->dropColumn('{{child}}', 'city');
        $this->dropColumn('{{child}}', 'school_city');

        $this->addColumn('{{child}}', 'known_relatives', 'varchar(255) NOT NULL');
	}
}