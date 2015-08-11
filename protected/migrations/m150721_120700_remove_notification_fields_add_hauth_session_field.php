<?php

class m150721_120700_remove_notification_fields_add_hauth_session_field extends CDbMigration
{
	public function safeUp()
	{
		$this->dropColumn('user', 'facebook_notification');
		$this->dropColumn('user', 'linked_in_notification');
		$this->dropColumn('user', 'twitter_notification');
		$this->dropColumn('user', 'google_plus_notification');

		$this->addColumn('user', 'hauth_session_data', 'varchar(4096)');
	}

	public function safeDown()
	{
		$this->addColumn('{{user}}', 'facebook_notification', 'bit NOT NULL');
		$this->addColumn('{{user}}', 'linked_in_notification', 'bit NOT NULL');
		$this->addColumn('{{user}}', 'twitter_notification', 'bit NOT NULL');
		$this->addColumn('{{user}}', 'google_plus_notification', 'bit NOT NULL');

		$this->dropColumn('user', 'hauth_session_data');
	}
}