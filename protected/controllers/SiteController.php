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
                  'actions' => array('index', 'error', 'login'),
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
        return array();
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->redirect(array('site/login'));
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

    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest) {
            $this->loginRedirect();
        }

        $this->layout = 'login';

        $model = new LoginForm();

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            $result            = $model->login();

            if ($result) {
                $this->loginRedirect();
            }
        }

        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    private function loginRedirect()
    {
        $user = User::model()->findByPk(Yii::app()->user->getId());

        if ($user->role_id == Role::x_admin) {
            $url = 'admin/dashboard';
        } elseif ($user->role_id == Role::x_host) {
            $url = 'host/dashboard';
        } elseif ($user->role_id == Role::x_intern) {
            $url = 'intern/dashboard';
        } else {
            $url = '/';
        }
        $this->redirect(array($url));
    }

}