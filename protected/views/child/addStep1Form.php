<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'child-info-form',
        'clientOptions'          => array(
            'validateOnSubmit' => true,
        ),
    ),

    'elements' => array(
        'child' => array(
            'type' => 'form',
            'title' => false,
            'elements' => array(
                'first_name' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'First Name',
                ),
                'middle_name' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Middle Name',
                ),
                'last_name' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Last Name',
                ),

                'gender' => array(
                    'type'  => 'dropdownlist',
                    'items' => Child::model()->getGenderOptions(),
                    'prompt'=>'Select gender',
                    'class' => 'fieldstyle',
                ),
                'ethnicity_id' => array(
                    'type'  => 'dropdownlist',
                    'items' => Ethnicity::model()->getOptions(),
                    'prompt'=>'Select ethnicity',
                    'class' => 'fieldstyle',
                ),
                'eyes_color_id' => array(
                    'type'  => 'dropdownlist',
                    'items' => EyesColor::model()->getOptions(),
                    'prompt'=>'Select eyes color',
                    'class' => 'fieldstyle',
                ),
                'hair_color_id' => array(
                    'type'  => 'dropdownlist',
                    'items' => HairColor::model()->getOptions(),
                    'prompt'=>'Select hair color',
                    'class' => 'fieldstyle',
                ),
                'address' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => "Address",
                ),
                'address2' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => "Address 2",
                ),
                'zip_code' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'City State Zip',
                ),
                'birthday' => array(
                    'type'  => 'date',
                    'class' => 'fieldstyle',
                    'label' => 'Date of Birth',
                ),
                'distinctive_marks' => array(
                    'type'  => 'textarea',
//                    'class' => 'fieldstyle',
                    'label' => 'Distinctive marks',
                    'rows' => '10',
                    'cols' => '50',
                ),
                'school' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'School',
                ),
                'school_address' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Address',
                ),
                'school_address2' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Address 2',
                ),
                'school_zip_code' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'City State Zip',
                ),
                'state' => array(
                    'type'  => 'dropdownlist',
                    'items' => Child::model()->getStateOptions(),
                    'prompt'=>'Select state',
                    'class' => 'fieldstyle',
                ),
                'school_state' => array(
                    'type'  => 'dropdownlist',
                    'items' => Child::model()->getStateOptions(),
                    'prompt'=>'Select state',
                    'class' => 'fieldstyle',
                ),
                'grade' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => "Grade",
                ),
            ),
        ),
    ),

    'buttons'    => array(
        'next_step' => array(
            'type'  => 'submit',
            'class' => 'btn',
            'label' => 'next»',
        ),
    ),
);