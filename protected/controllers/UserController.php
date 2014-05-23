<?php

class UserController extends Controller
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
                  'actions' => array('register', 'login', 'forgotPassword', 'passwordResetLinkSent', 'passwordReset',
                                     'passwordResetSuccess', 'registrationSuccess', 'activateAccount'),
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

    public function actionRegister()
    {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(array('child/add', 'step' => 'step1'));
        }

        $form = new CForm('application.views.user.registrationForm');

        $form['user']->model = new User('registration');

        if ($form->submitted('register')) {
            $form['user']->model->username = $form['user']->model->email;

            if ($form->validate()) {
                /** @var User $user */
                $user = $form['user']->model;
                $user->password  = User::hashPassword($user->password);

                if ($user->save(false)) {
                    $this->sendActivationCodeEmail($user->primaryKey);
                    $this->redirect(array('user/registrationSuccess'));
                }
            }
        }

        $this->render(
            'register', array(
                'form' => $form,
            )
        );
    }

    public function actionForgotPassword()
    {

        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->homeUrl);
        }

        $this->layout = 'main';

        $model = new PasswordResetForm();

        // collect user input data
        if (isset($_POST['PasswordResetForm'])) {
            $model->attributes = $_POST['PasswordResetForm'];

            if ($model->resetPassword()) {
                $this->redirect(array('user/passwordResetLinkSent'));
            }
        }

        $this->render('forgotPassword', array('model' => $model));
    }

    public function actionRegistrationSuccess()
    {
        $this->render('registrationSuccess', array());
    }

    public function actionActivateAccount()
    {
        $user = User::model()->find(
            array(
                'condition' => 'verification_code = :verification_code',
                'params'    => array(':verification_code' => $_GET['verification_code']),
            )
        );

        if ($user) {
            $user->is_active = 1;
            $user->save();
            $this->render('accountActivated');
        } else {
            $this->render('accountActivationWrongLink');
        }
    }

    public function actionPasswordResetLinkSent()
    {
        $this->layout = 'main';
        $this->render('passwordResetLinkSent', array());
    }

    public function actionPasswordReset()
    {
        $this->layout = 'main';

        $code = Yii::app()->request->getParam('code', false);

        if (!$code) {
            throw new CHttpException(400, 'Code parameter not provided!');
        }

        /** @var User $user */
        $user = User::model()->findByAttributes(array('password_reset_code' => $code));

        if (!$user) {
            throw new CHttpException(404, 'The link is incorrect or code is expired!');
        }

        $user->scenario = 'passwordReset';

        $model = new ChangePasswordForm();

        // collect user input data
        if (isset($_POST['ChangePasswordForm'])) {

            $model->attributes = $_POST['ChangePasswordForm'];

            if ($model->validate()) {
                $user->password = $user->hashPassword($model->password);
                $user->password_reset_code = '';

                if ($user->save(true, array('password', 'password_reset_code'))) {
                    $this->sendActivationCodeEmail($user->primaryKey);

                    $this->redirect(array('user/passwordResetSuccess'));
                }
            }
        }


        $this->render('passwordReset', array('model' => $model));
    }

    public function actionPasswordResetSuccess()
    {
        $this->layout = 'main';
        $this->render('passwordResetSuccess', array());
    }

    private function sendActivationCodeEmail($userId)
    {
        /** @var User $user */
        $user = User::model()->findByPk($userId);
        $verification_code = $user->verification_code;

        if (!$verification_code) {
            $verification_code = md5($user->id);
            User::model()->updateByPk(
                $user->id,
                array( 'verification_code' => $verification_code )
            );
        }

        $baseUrl = Yii::app()->getRequest()->getBaseUrl(true);

        $result = Yii::app()->common->sendEmail(
            $user->email,
            'Final Step: Confirm your email address to activate your page',
            'account_activation',
            array(
                'username' => $user->name,
                'activation_link' => $baseUrl . '/user/activate-account?verification_code=' . $verification_code,
            )
        );

        return $result;
    }

    public function actionLogin()
    {
        $this->layout = 'main';
        $model=new LoginForm;

        if(isset($_POST['LoginForm'])) {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                if (!Yii::app()->user->getIsActive()) {
                    Yii::app()->user->logout();
                    $this->render('error', array('message' => 'You have not activated your account. Please activate your account before entering the site. Activation instructions sent to your email.'));
                } else {
                    if (Child::model()->findAll('user_id = :user_id', array(':user_id' => Yii::app()->user->getId()))) {
                        $this->redirect(Yii::app()->homeUrl);
                    } else {
                        $this->redirect(array('child/add', 'step' => 'step1'));
                    }
                }
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


}