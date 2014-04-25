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
        <?php
    }
    ?>
</div><!-- form -->