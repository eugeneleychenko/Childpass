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
        <div style="float: left; width: 50px; margin-top: 8px;"><h6 style="color: #002f65; font-weight: 500;">Log In</h6></div>
        <input type="hidden" name="csrf_test_name" value="cee0db86402bed7618a8f9daa3a194cc">
        <div style="float: left; width: 136px; margin-top: 7px;">
            <?php echo $form->textField($model,'username', array('placeholder' => 'Email', 'style' => 'border: 1px solid grey; font-size: 15px; width: 111px; height: 16px; padding-left: 8px; margin-bottom: 5px;')) ?>
            <?php echo $form->passwordField($model,'password', array('placeholder' => 'Password', 'style' => 'border: 1px solid grey; width: 111px; height: 16px; font-size: 15px; padding-left: 8px;')) ?>
            <?php echo $form->hiddenField($model,'remember', array('value' => true)) ?>
        </div>
        <div style="float: left; width: 103px; margin-top: 9px;">
            <?php echo CHtml::submitButton('login', array('class' => 'btn', 'name' => 'submit', 'value' => 'go»')); ?>
            <div style="float: left; font-family: Arial; font-size: 13px; color: #ee8b38;">Forgot password?</div>
        </div>
        <?php $this->endWidget();
    } else {
        ?>
        <p>Welcome, <span><?php echo Yii::app()->user->getName() ?></span></p>
        <?php
        echo CHtml::link('log out»', array('user/logout'));
    }
    ?>
</div><!-- form -->