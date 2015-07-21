<?php

class SocialHelper extends CComponent
{
    public function init() {

    }

    public function postAlert($childId)
    {
        require_once(dirname(__FILE__).'/../extensions/hoauth/models/UserOAuth.php');

        $content = self::createContent($childId);

        $hauth = UserOAuth::model()->getHybridAuth();

        $api = array();
        foreach($hauth::getConnectedProviders() as $provider) {
            $api[$provider] = $hauth->getAdapter($provider)->api();
        };

        if (array_key_exists('Twitter', $api)) {

            $output = $api['Twitter']->api('https://upload.twitter.com/1.1/media/upload.json', 'POST', array(
                'media_data' => base64_encode(file_get_contents($content['photoPath']))
            ));

            $api['Twitter']->api('statuses/update.json', 'POST', array(
                'status' => $content['title'] . ' ' . $content['flyerUrl'],
                'media_ids' => $output->media_id_string
            ));
        }

        if (array_key_exists('LinkedIn', $api)) {
            $api['LinkedIn']->share(
                'new',
                array(
                    'title' => $content['title'],
                    'submitted-url' => $content['flyerUrl'],
                    'submitted-image-url' => $content['photoUrl'],
                    'description' => $content['description'],
                ),
                false,
                false
            );
        }

        if (array_key_exists('Facebook', $api)) {
            $api['Facebook']->api(
                '/me/feed', 'POST',
                array(
                    'message' => $content['title'] . "\n\n" . $content['description'],
                    'picture' => $content['photoUrl'],
                    'link' => $content['flyerUrl'],
                    'name'    => $content['title'],
                    'caption' => $content['description']
                )
            );
        }
    }

    private function createContent($childId)
    {
        $missingInfo = Child::model()->getMissingInfo($childId);

        $content = array();

        $content['title'] = 'Alert! ';
        $content['title'] .= $missingInfo['age'] . '-' . ($missingInfo['age'] % 10 === 1 ? 'year old ' : 'years old ');
        $content['title'] .= $missingInfo['child']->gender === 'M' ? 'boy ' : 'girl ';
        $content['title'] .= 'missed!';

        $content['photoUrl'] = $missingInfo['childPhoto'];
        $content['photoPath'] = $missingInfo['childPhotoPath'];
        $content['flyerUrl'] = Yii::app()->getBaseUrl(true) . '/child/generate-flyer/' . $missingInfo['child']->id;

        $content['description'] = $missingInfo['incident']->child_description . "\n\n" . $missingInfo['incident']->description;

        return $content;
    }
}