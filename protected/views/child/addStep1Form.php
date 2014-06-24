<?php
return array(
    'title'      => '',
    'activeForm' => array(
        'class'                  => 'CActiveForm',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'id'                     => 'child-info-form',
        'clientOptions'          => array(
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
                ),
                'middle_name' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                ),
                'last_name' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
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
                    'prompt'=>'Select eye color',
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
                    'placeholder' => 'Please include PO Box or Apartment number, if applicable'
                ),
                'address2' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                ),
                'zip_code' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Zip',
                ),
                'birthday' => array(
                    'type' => 'date',
                    'class' => 'fieldstyle datepicker',
                    'label' => 'Date of Birth',
                ),
                'distinctive_marks' => array(
                    'type'  => 'textarea',
                    'rows' => 10,
                    'label' => 'Please provide details such as location of scars, birthmarks, tattoos or any other marks',
                    'maxlength' => '255',
                    'class' => 'distinctive_marks'
                ),
                'school' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'School Name',
                ),
                'school_address' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Address',
                    'placeholder' => 'Please include PO Box or Apartment number, if applicable'
                ),
                'school_address2' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Address 2',
                ),
                'school_zip_code' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'label' => 'Zip',
                ),
                'state' => array(
                    'type'  => 'dropdownlist',
                    'items' => Child::model()->getStateOptions(),
                    'prompt'=>'Select state',
                    'class' => 'fieldstyle'
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
                ),
                'additional_school_details' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                    'placeholder' => 'Special Programs/Clubs/Sports'
                ),
                'city' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
                ),
                'school_city' => array(
                    'type'  => 'text',
                    'class' => 'fieldstyle',
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