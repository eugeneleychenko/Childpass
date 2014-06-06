<?php

class m140529_132503_relative extends CDbMigration
{


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->createTable('{{relation}}', array(
            'id'                => 'pk',
            'name'              => 'nvarchar(50) NOT NULL',
        ));

        $this->insert('{{relation}}', array(
                'id' => 1,
                'name' => 'Father')
        );
        $this->insert('{{relation}}', array(
                'id' => 2,
                'name' => 'Mother')
        );
        $this->insert('{{relation}}', array(
                'id' => 3,
                'name' => 'Grandfather')
        );
        $this->insert('{{relation}}', array(
                'id' => 4,
                'name' => 'Grandmother')
        );
        $this->insert('{{relation}}', array(
                'id' => 5,
                'name' => 'Uncle')
        );
        $this->insert('{{relation}}', array(
                'id' => 6,
                'name' => 'Aunt')
        );

        $this->createTable('{{relative}}', array(
            'id'                => 'pk',
            'first_name'              => 'nvarchar(100) NOT NULL',
            'last_name'              => 'nvarchar(100) NOT NULL',
        ));


        $this->createTable('{{child_relative}}', array(
            'id'                => 'pk',
            'child_id'          => 'integer NOT NULL',
            'relative_id'       => 'integer NOT NULL',
            'relation_id'       => 'integer NOT NULL'
        ));

        $this->addForeignKey('{{child_relative}}_{{relative}}_FK',
            '{{child_relative}}',
            'relative_id',
            '{{relative}}',
            'id'
        );

        $this->addForeignKey('{{child_relative}}_{{child}}_FK',
            '{{child_relative}}',
            'child_id',
            '{{child}}',
            'id'
        );

        $this->addForeignKey('{{child_relative}}_{{relation}}_FK',
            '{{child_relative}}',
            'relation_id',
            '{{relation}}',
            'id'
        );

        $this->createIndex('child_id_relative_id', '{{child_relative}}', 'child_id,relative_id', true);
    }

	public function safeDown()
	{
        $this->dropTable('{{child_relative}}');
        $this->dropTable('{{relation}}');
        $this->dropTable('{{relative}}');
	}

}