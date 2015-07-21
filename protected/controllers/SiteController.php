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

    public function actionSetReturnUrl()
    {
        $url = $_GET['url'];
        Yii::app()->user->setReturnUrl($url);
    }
}