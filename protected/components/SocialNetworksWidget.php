<?php

class SocialNetworksWidget extends CWidget
{
    public function run() {

        require_once(dirname(__FILE__).'/../extensions/hoauth/models/UserOAuth.php');
        $hauth = UserOAuth::model()->getHybridAuth();

        $this->render('socialNetworks', array('connectedProviders' => $hauth::getConnectedProviders()));
    }
}