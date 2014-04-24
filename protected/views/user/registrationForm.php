<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'registration-form',
        'clientOptions'          => array(
            'validateOnSubmit' => true,
        ),
    ),

    'elements'   => array(
        'user' => array(
            'type'     => 'form',
            'title'    => false,
            'elements' => array(
                'email'    => array(
                    'label' => 'Your email address',
                    'class' => 'fieldstyle',
                    'type'  => 'email',
                ),
                'password' => array(
                    'type' => 'password',
                    'class' => 'fieldstyle',
                ),
                'name'     => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Your Name',
                ),
            ),
        ),
    ),

    'buttons'    => array(
        'register' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => 'next»',
        ),
    ),
);