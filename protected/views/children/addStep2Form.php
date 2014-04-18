<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'child-photo-form',
        'clientOptions'          => array(
            'validateOnSubmit' => true,
        ),
    ),

    'elements' => array(
        'children' => array(
            'type' => 'form',
            'title' => false,
            'elements' => array(
                'image' => array(
                    'type'  => 'file',
                    'class' => 'fieldstyle',
//                    'label' => "Your Child's Name",
                ),
            ),
        ),
    ),

    'buttons'    => array(
        'addStep2' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => 'next»',
        ),
    ),
);