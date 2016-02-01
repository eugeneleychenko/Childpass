<?php

class m151006_155936_user_add_order_number_field extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('{{user}}', 'order_number', 'VARCHAR(50) NULL');
	}

	public function safeDown()
	{
		$this->dropColumn('{{user}}', 'order_number');
	}
}