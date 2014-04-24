<?php

class m140417_135340_child extends CDbMigration
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

        $this->createTable('{{child}}', array(
            'id'                    => 'pk',
            'user_id'               => 'integer NOT NULL',
            'created_at'            => 'timestamp NOT NULL',
            'name'                  => 'varchar(100) NOT NULL',
            'gender'                => 'varchar(1) NOT NULL',
            'height_feet'           => 'integer(1) NULL',
            'height_inches'         => 'integer(2) NULL',
            'weight'                => 'integer(3) NULL',
            'ethnicity_id'          => 'integer(10) NOT NULL',
            'eyes_color_id'         => 'integer(10) NOT NULL',
            'hair_color_id'         => 'integer(10) NOT NULL',
            'address'               => 'varchar(100) NOT NULL',
            'address2'              => 'varchar(100) NOT NULL',
            'zip_code'              => 'varchar(10) NOT NULL',
            'birthday'              => 'datetime NOT NULL',
            'distinctive_marks'     => 'varchar(255) NOT NULL',
            'school'                => 'varchar(150) NOT NULL',
            'school_address'        => 'varchar(100) NOT NULL',
            'school_address2'       => 'varchar(100) NOT NULL',
            'school_zip_code'       => 'varchar(10) NOT NULL',
            'known_relatives'       => 'varchar(255) NOT NULL',
            'teeth'                 => 'text NULL',
            'missing_date'          => 'datetime',
            'missing_from'          => 'varchar(255) NULL',
        ));

        $this->addForeignKey('{{child}}_{{user}}_FK',
            '{{child}}',
            'user_id',
            '{{user}}',
            'id'
        );

        $this->addForeignKey('{{child}}_{{ethnicity}}_FK',
            '{{child}}',
            'ethnicity_id',
            '{{ethnicity}}',
            'id'
        );
        $this->addForeignKey('{{child}}_{{eyes_color}}_FK',
            '{{child}}',
            'eyes_color_id',
            '{{eyes_color}}',
            'id'
        );
        $this->addForeignKey('{{child}}_{{hair_color}}_FK',
            '{{child}}',
            'hair_color_id',
            '{{hair_color}}',
            'id'
        );

        $this->createIndex('user_id', '{{child}}', 'user_id');
        $this->createIndex('gender', '{{child}}', 'gender');
        $this->createIndex('height_feet', '{{child}}', 'height_feet');
        $this->createIndex('height_inches', '{{child}}', 'height_inches');
        $this->createIndex('weight', '{{child}}', 'weight');
        $this->createIndex('ethnicity_id', '{{child}}', 'ethnicity_id');
        $this->createIndex('eyes_color_id', '{{child}}', 'eyes_color_id');
        $this->createIndex('hair_color_id', '{{child}}', 'hair_color_id');

        $this->createTable('{{child_photo}}', array(
            'id'              => 'pk',
            'created_at'      => 'timestamp NOT NULL',
            'child_id'        => 'integer NOT NULL',
            'filename'        => 'varchar(100) NOT NULL',
            'is_main'         => 'tinyint(1) DEFAULT 0 NOT NULL',
        ));

        $this->addForeignKey('{{child_photo}}_{{child}}_FK',
            '{{child_photo}}',
            'child_id',
            '{{child}}',
            'id'
        );
        $this->createIndex('child_id', '{{child_photo}}', 'child_id');

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
        $this->dropTable('{{child_photo}}');
        $this->dropTable('{{child}}');
        $this->dropTable('{{hair_color}}');
        $this->dropTable('{{eyes_color}}');
        $this->dropTable('{{ethnicity}}');
	}
}