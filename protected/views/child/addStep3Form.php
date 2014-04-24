<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => false,
        'id'                     => 'child-teeth-form',
        'clientOptions'          => array(
            'validateOnSubmit' => false,
        )
    ),

    'elements' => array(
        'child' => array(
            'type' => 'form',
            'title' => false,
            'elements' => array(
                'teeth' => array(
                    'type'  => 'hidden',
                    'id' => 'teeth_array',
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