<?php

class SiteController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                  'users' => array('@'),
            ),
            array('allow',
                  'actions' => array('index', 'error', 'login', 'page', 'social', 'oauth', 'oauthadmin'),
                  'users'   => array('*')
            ),
            array('deny'),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'page' => array(
                'class' => 'CViewAction'
            ),
            'oauth' => array(
                // the list of additional properties of this action is below
                'class'=>'ext.hoauth.HOAuthAction',
                // Yii alias for your user's model, or simply class name, when it already on yii's import path
                // default value of this property is: User
                'model' => 'User',
                // map model attributes to attributes of user's social profile
                // model attribute => profile attribute
                // the list of avaible attributes is below
                'attributes' => array(
                    'email' => 'email',
                    /*'fname' => 'firstName',
                    'lname' => 'lastName',
                    'gender' => 'genderShort',
                    'birthday' => 'birthDate',*/
                    // you can also specify additional values,
                    // that will be applied to your model (eg. account activation status)
                    'acc_status' => 1
                )
            ),
            // this is an admin action that will help you to configure HybridAuth
            // (you must delete this action, when you'll be ready with configuration, or
            // specify rules for admin role. User shouldn't have access to this action!)
            'oauthadmin' => array(
                'class'=>'ext.hoauth.HOAuthAdminAction'
            )
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        //$this->redirect(array('site/login'));
        $this->render('home', array());
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                $this->layout = 'ajax';
                echo $error['message'];
            } else {
                $this->layout = 'login';
                $this->render('error', $error);
            }
        }
    }

    public function actionSocial()
    {
        //$this->layout = false;
        $this->render('social', array('user_id' => Yii::app()->user->getId()));
    }

    public function actionPost()
    {
        require_once(dirname(__FILE__).'/../extensions/hoauth/models/UserOAuth.php');

        $hauth = UserOAuth::model()->getHybridAuth();

        $providers = $hauth::getConnectedProviders();
        //$providers = $hauth::getProviders();
         $api = array();

        foreach($providers as $provider) {
            $api[$provider] = $hauth->getAdapter($provider)->api();
        };

        $li_response = $api['LinkedIn']->share(
            'new',
            array(
                'comment' => 'api test comment',
                'title' => 'api test title',
                'submitted-url' => 'http://google.com',
                'submitted-image-url' => 'https://dl.dropboxusercontent.com/u/71669067/salat_paportnik.jpg',
                'description' => "api test description api test description\napi \n api test description"
            ),
            false,
            false
        );

        /*$twi_response = $api['Twitter']->api('statuses/update.json', 'POST', array(
            'status' => 'Wow. It works! (new test tweet)'
        ));*/

        /*$fb_response = $api['Facebook']->api("/me/feed", "POST", array(
            'message' => "Hi there. Test message from my application under development. Forget it.",
            //picture => "http://www.mywebsite.com/path/to/an/image.jpg",
            //link => "http://www.mywebsite.com/path/to/a/page/",
            'name' => "My page name",
            'caption' => "And caption"
        ));*/

        var_dump($li_response);
    }
}