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

        $this->insert('{{relation}}', array(
                'id' => 7,
                'name' => 'Sister')
        );

        $this->insert('{{relation}}', array(
                'id' => 8,
                'name' => 'Brother')
        );

        $this->insert('{{relation}}', array(
                'id' => 9,
                'name' => 'Caregiver')
        );

	}

	public function safeDown()
	{
        $this->alterColumn('{{child}}', 'grade', 'integer NOT NULL');

        $this->dropColumn('{{child}}', 'additional_school_details');

        $this->addColumn('{{child}}', 'address2', 'varchar(100) NOT NULL');
        $this->addColumn('{{child}}', 'school_address2', 'varchar(100) NOT NULL');

        $this->delete('{{child_relative}}','relation_id = 7 OR relation_id = 8 OR relation_id = 9');
        $this->delete('{{relation}}','id = 7 OR id = 8 OR id = 9');
	}

}