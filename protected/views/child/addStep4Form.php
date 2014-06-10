<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'child-alert-settings-form',
        'clientOptions'          => array(
            'validateOnSubmit' => true,
        ),
    ),

    'elements' => array(
        'child' => array(
            'type' => 'form',
            'title' => false,
            'elements' => array(
                'notification_emails' => array(
                    'type'  => 'textarea',
                    'rows' => 10,
                    'label' => 'Please enter each email address separated by commas',
                    'maxlength' => '255',
                ),
                'facebook_notification' => array(
                    'type'  => 'checkbox',
                    'class' => 'fieldstyle',
                    'label' => 'Facebook',
                ),
                'linked_in_notification' => array(
                    'type'  => 'checkbox',
                    'class' => 'fieldstyle',
                    'label' => 'LinkedIn',
                ),
                'twitter_notification' => array(
                    'type'  => 'checkbox',
                    'class' => 'fieldstyle',
                    'label' => 'Twitter',
                ),
                'google_plus_notification' => array(
                    'type'  => 'checkbox',
                    'class' => 'fieldstyle',
                    'label' => 'Google Plus',
                ),
          ),
        ),
    ),

    'buttons'    => array(
        'prev_step' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => '«prev',
        ),
        'next_step' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => 'next»',
        ),
    ),
);