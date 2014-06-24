<?php

class m140624_102127_change_child_fields extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->alterColumn('{{child}}', 'grade', 'varchar(5) NOT NULL');
        $this->addColumn('{{child}}', 'additional_school_details', 'varchar(100) NOT NULL');

        $this->dropColumn('{{child}}', 'address2');
        $this->dropColumn('{{child}}', 'school_address2');
	}

	public function safeDown()
	{
        $this->alterColumn('{{child}}', 'grade', 'integer NOT NULL');

        $this->dropColumn('{{child}}', 'additional_school_details');

        $this->addColumn('{{child}}', 'address2', 'varchar(100) NOT NULL');
        $this->addColumn('{{child}}', 'school_address2', 'varchar(100) NOT NULL');
	}

}