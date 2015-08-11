<?php

class SocialNetworkController extends Controller
{
    public function actionDisconnectUser()
    {
        if (!isset($_GET['provider'])) {
            throw new HttpRequestException('No `provider` parameter in request.');
        }

        require_once(dirname(__FILE__).'/../extensions/hoauth/models/UserOAuth.php');

        $hauth = UserOAuth::model()->getHybridAuth();

        $provider = $_GET['provider'];
        $adapter = $hauth::getAdapter($provider);
        $adapter->logout();

        UserOAuth::model()->deleteAllByAttributes(
            array(
                'user_id' => Yii::app()->user->getId(),
                'provider' => $provider
            )
        );

        $sessionData = $hauth::getSessionData();
        $user = User::model()->findByPk(Yii::app()->user->getId());
        $user->hauth_session_data = $sessionData;
        $user->save();

        return true;
    }
}