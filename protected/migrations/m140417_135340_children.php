<?php

class m140417_135340_children extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('{{ethnicity}}', array(
            'id'                    => 'pk',
            'name'                  => 'varchar(100) NOT NULL',
        ));

        $this->createTable('{{eyes_color}}', array(
            'id'                    => 'pk',
            'name'                  => 'varchar(100) NOT NULL',
        ));

        $this->createTable('{{hair_color}}', array(
            'id'                    => 'pk',
            'name'                  => 'varchar(100) NOT NULL',
        ));

        $this->createTable('{{children}}', array(
            'id'                    => 'pk',
            'created_at'            => 'timestamp NOT NULL',
            'name'                  => 'varchar(100) NULL',
            'sex'                   => 'varchar(1) NULL',
            'height_feet'           => 'integer(1) NULL',
            'height_inches'         => 'integer(2) NULL',
            'weight'                => 'integer(3) NULL',
            'ethnicity_id'          => 'integer(10) NULL',
            'eyes_color_id'         => 'integer(10) NULL',
            'hair_color_id'         => 'integer(10) NULL',
            'address'               => 'varchar(100) NULL',
            'address2'              => 'varchar(100) NULL',
            'zip_code'              => 'varchar(10) NULL',
            'birthday'              => 'datetime',
            'distinctive_marks'     => 'varchar(255) NULL',
            'school'                => 'varchar(150) NULL',
            'school_address'        => 'varchar(100) NULL',
            'school_address2'       => 'varchar(100) NULL',
            'school_zip_code'       => 'varchar(10) NULL',
            'known_relatives'       => 'varchar(255) NULL',
            'missing_date'          => 'datetime',
            'missing_from'          => 'varchar(255) NULL',
        ));

        $this->addForeignKey('{{children}}_{{ethnicity}}_FK',
            '{{children}}',
            'ethnicity_id',
            '{{ethnicity}}',
            'id'
        );
        $this->addForeignKey('{{children}}_{{eyes_color}}_FK',
            '{{children}}',
            'eyes_color_id',
            '{{eyes_color}}',
            'id'
        );
        $this->addForeignKey('{{children}}_{{hair_color}}_FK',
            '{{children}}',
            'hair_color_id',
            '{{hair_color}}',
            'id'
        );

        $this->createIndex('sex', '{{children}}', 'sex');
        $this->createIndex('height_feet', '{{children}}', 'height_feet');
        $this->createIndex('height_inches', '{{children}}', 'height_inches');
        $this->createIndex('weight', '{{children}}', 'weight');
        $this->createIndex('ethnicity_id', '{{children}}', 'ethnicity_id');
        $this->createIndex('eyes_color_id', '{{children}}', 'eyes_color_id');
        $this->createIndex('hair_color_id', '{{children}}', 'hair_color_id');

        $this->createTable('{{children_relation}}', array(
            'id'                    => 'pk',
            'user_id'               => 'integer NOT NULL',
        ));

        $this->addForeignKey('{{children_relation}}_{{user}}_FK',
            '{{children_relation}}',
            'user_id',
            '{{user}}',
            'id'
        );
        $this->createIndex('user_id', '{{children_relation}}', 'user_id');

        $this->createTable('{{children_photo}}', array(
            'id'                 => 'pk',
            'created_at'         => 'timestamp NOT NULL',
            'children_id'        => 'integer NOT NULL',
            'filename'           => 'varchar(100) NOT NULL',
        ));

        $this->addForeignKey('{{children_photo}}_{{children}}_FK',
            '{{children_photo}}',
            'children_id',
            '{{children}}',
            'id'
        );
        $this->createIndex('children_id', '{{children_photo}}', 'children_id');

        $this->insert('{{ethnicity}}', array( "id"   => 1, "name" => 'White'));
        $this->insert('{{ethnicity}}', array( "id"   => 2, "name" => 'Asian'));
        $this->insert('{{ethnicity}}', array( "id"   => 3, "name" => 'Black'));
        $this->insert('{{ethnicity}}', array( "id"   => 4, "name" => 'Hispanic / Latin'));
        $this->insert('{{ethnicity}}', array( "id"   => 5, "name" => 'Indian'));
        $this->insert('{{ethnicity}}', array( "id"   => 6, "name" => 'Middle Eastern'));

        $this->insert('{{eyes_color}}', array( "id"   => 1, "name" => 'Amber'));
        $this->insert('{{eyes_color}}', array( "id"   => 2, "name" => 'Black'));
        $this->insert('{{eyes_color}}', array( "id"   => 3, "name" => 'Blue'));
        $this->insert('{{eyes_color}}', array( "id"   => 4, "name" => 'Brown'));
        $this->insert('{{eyes_color}}', array( "id"   => 5, "name" => 'Gray'));
        $this->insert('{{eyes_color}}', array( "id"   => 6, "name" => 'Green'));
        $this->insert('{{eyes_color}}', array( "id"   => 7, "name" => 'Hazel'));
        $this->insert('{{eyes_color}}', array( "id"   => 8, "name" => 'Violet'));
        $this->insert('{{eyes_color}}', array( "id"   => 9, "name" => 'Red'));

        $this->insert('{{hair_color}}', array( "id"   => 1, "name" => 'Black'));
        $this->insert('{{hair_color}}', array( "id"   => 2, "name" => 'Brown'));
        $this->insert('{{hair_color}}', array( "id"   => 3, "name" => 'Blond'));
        $this->insert('{{hair_color}}', array( "id"   => 4, "name" => 'Auburn'));
        $this->insert('{{hair_color}}', array( "id"   => 5, "name" => 'Chestnut'));
        $this->insert('{{hair_color}}', array( "id"   => 6, "name" => 'Red'));
        $this->insert('{{hair_color}}', array( "id"   => 7, "name" => 'Gray'));
        $this->insert('{{hair_color}}', array( "id"   => 8, "name" => 'White'));
	}

	public function safeDown()
	{
        $this->dropTable('{{children_photo}}');
        $this->dropTable('{{children_relation}}');
        $this->dropTable('{{children}}');
        $this->dropTable('{{hair_color}}');
        $this->dropTable('{{eyes_color}}');
        $this->dropTable('{{ethnicity}}');
	}
}