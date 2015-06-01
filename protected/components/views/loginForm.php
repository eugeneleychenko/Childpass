<div class="login-form">
    <?php
    if (Yii::app()->user->isGuest) {

        /** @var $form  CActiveForm */
        $form = $this->beginWidget('CActiveForm', array(
            'enableClientValidation' => true,
            'id' => 'form-login',
            'action' => array('user/login'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'hideErrorMessage' => true,
            )
        )); ?>
        <?php echo $form->errorSummary($model, ''); ?>
        <div class="login-label">Log In</div>
        <div class="login-fields">
            <?php echo $form->textField($model,'username', array('placeholder' => 'Email')) ?>
            <?php echo $form->passwordField($model,'password', array('placeholder' => 'Password')) ?>
            <?php echo $form->hiddenField($model,'remember', array('value' => true)) ?>
        </div>
        <div class="login-buttons">
            <?php echo CHtml::submitButton('login', array('class' => 'btn', 'name' => 'submit', 'value' => 'go»')); ?>
            <a class="forgot-password" href="<?php echo $this->createUrl('user/forgotPassword') ?>">Forgot password?</a>
        </div>
        <?php $this->endWidget();
    } else {
        ?>
        <div class="logout-buttons">
            <p class="welcome-label">Welcome, <span><?php echo Yii::app()->user->getName() ?></span></p>
            <?php echo CHtml::link('log out»', array('user/logout'), array('class' => 'btn logout')); ?>
        </div>

        <!--<div id="oa_social_link_container"></div>

        <script type="text/javascript">

            /* Replace #your_callback_uri# with the url to your own callback script */
            var your_callback_script = 'https://childpass.local/user/callback';

            /* Dynamically add the user_token of the currently logged in user. */
            /* If the user has no user_token then leave the field blank. */
            var user_token = '64109c8a-fd52-4544-bc31-31a83f912dcb';

            /* Embeds the buttons into the container oa_social_link_container */
            var _oneall = _oneall || [];
            _oneall.push(['social_link', 'set_providers', ['facebook', 'google', 'twitter']]);
            _oneall.push(['social_link', 'set_callback_uri', your_callback_script]);
            _oneall.push(['social_link', 'set_user_token', user_token]);
            _oneall.push(['social_link', 'do_render_ui', 'oa_social_link_container']);

        </script>-->


        <?php
    }
    ?>
</div><!-- form -->