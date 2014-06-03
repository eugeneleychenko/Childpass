<?php

class m140603_134347_incident extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->createTable('{{incident}}', array(
            'id'                    => 'pk',
            'child_id'              => 'integer NOT NULL',
            'child_description'     => 'varchar(255) NOT NULL',
            'description'           => 'varchar(255) NOT NULL',
            'date'                  => 'datetime NOT NULL',
        ));

        $this->addForeignKey('{{incident}}_{{child}}_FK',
            '{{incident}}',
            'child_id',
            '{{child}}',
            'id'
        );
        $this->createIndex('child_id', '{{incident}}', 'child_id', true);
	}

	public function safeDown()
	{
        $this->dropTable('{{incident}}');
	}
}