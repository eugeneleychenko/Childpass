<?php

/**
 * SurveyForm class.
 */
class SurveyForm extends CFormModel
{
    public $like_survey;
    public $like_kit;
    public $kit_opinion;
    public $kit_rate;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('like_survey, like_kit, kit_rate', 'required'),
            array('like_survey, like_kit, kit_rate', 'notEmpty'),
            array('kit_opinion', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'like_survey' => 'Do you like this survey?',
            'like_kit' => 'Do you like this kit?',
            'kit_opinion' => 'What do you think of this kit (ie, sizing, quality, etc)?',
            'kit_rate' => 'Rate this kit:'
        );
    }

    public function notEmpty($attribute,$params)
    {

        if ($this->$attribute == '0')
            $this->addError($attribute, 'Question is required!');

    }
}