<?php

class m140610_103605_add_user_alert_settings extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $this->addColumn('{{user}}', 'facebook_notification', 'bit NOT NULL');
        $this->addColumn('{{user}}', 'linked_in_notification', 'bit NOT NULL');
        $this->addColumn('{{user}}', 'twitter_notification', 'bit NOT NULL');
        $this->addColumn('{{user}}', 'google_plus_notification', 'bit NOT NULL');
        $this->addColumn('{{user}}', 'notification_emails', 'text NOT NULL');
	}

	public function safeDown()
	{
        $this->dropColumn('{{user}}', 'facebook_notification');
        $this->dropColumn('{{user}}', 'linked_in_notification');
        $this->dropColumn('{{user}}', 'twitter_notification');
        $this->dropColumn('{{user}}', 'google_plus_notification');
        $this->dropColumn('{{user}}', 'notification_emails');
	}

}