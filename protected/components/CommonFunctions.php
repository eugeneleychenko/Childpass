<?php

class CommonFunctions extends CComponent
{

    public function init() {
    }

    public function sendEmail($to, $subject, $template, $data = array(), $from = false)
    {
        $from = ($from) ?: Yii::app()->params['adminEmail'];

        $mail = new YiiMailer($template, $data);

        $mail->setFrom($from);
        $mail->setTo($to);
        $mail->setSubject($subject);

        return $mail->send();
    }
}