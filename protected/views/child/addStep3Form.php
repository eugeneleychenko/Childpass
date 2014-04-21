<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'child-dental-form',
        'clientOptions'          => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions'            => array(
            'enctype' => 'multipart/form-data',
        ),
    ),

    'elements' => array(
        'child' => array(
            'type' => 'form',
            'title' => false,
            'elements' => array(
//                'image' => array(
//                    'type'  => 'file',
//                    'class' => 'fieldstyle',
//                ),
            ),
        ),
    ),

    'buttons'    => array(
        'addStep3' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => 'next»',
        ),
    ),
);