<?php

class m150722_154137_add_user_o_auth_table extends CDbMigration
{
	public function safeUp()
	{
		$this->execute('
			SET FOREIGN_KEY_CHECKS=0;
			SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
			SET time_zone = "+00:00";

			DROP TABLE IF EXISTS `{{user_oauth}}`;
			CREATE TABLE IF NOT EXISTS `{{user_oauth}}` (
				`user_id` int(11) NOT NULL,
			  	`provider` varchar(45) NOT NULL,
			  	`identifier` varchar(64) NOT NULL,
			  	`profile_cache` text,
			  	`session_data` text,
			  	PRIMARY KEY (`provider`,`identifier`),
			  	UNIQUE KEY `unic_user_id_name` (`user_id`,`provider`),
			  	KEY `oauth_user_id` (`user_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			SET FOREIGN_KEY_CHECKS=1;'
		);
	}

	public function safeDown()
	{
		$this->dropTable('{{user_oauth}}');
	}
}