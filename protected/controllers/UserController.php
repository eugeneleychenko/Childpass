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
            $this->redirect(array('children/add/step1'));
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
//                    Yii::app()->common->sendEmail(
//                        $user->email, 'Thank you for registering as a 2014 PGPF Fiscal Internship host!',
//                        'host_registration_success'
//                    );

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

        // collect user input data
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];

            $user->password            = $user->hashPassword($user->password);
            $user->password_reset_code = '';

            if ($user->save(true, array('password', 'password_reset_code'))) {
                $this->sendActivationCodeEmail($user->primaryKey);

                $this->redirect(array('user/passwordResetSuccess'));
            }
        }

        $user->password = '';

        $this->render('passwordReset', array('model' => $user));
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

        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        // display the login form
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