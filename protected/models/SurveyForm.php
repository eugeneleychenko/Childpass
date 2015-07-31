<?php

/**
 * SurveyForm class.
 */
class SurveyForm extends CFormModel
{
    public $ever_purchased;
    public $ever_purchased_yes;
    public $how_satisfied;
    public $tell_us;
    public $purchase_yourself;
    public $purchase_yourself_not;
    public $purchase_family;
    public $purchase_family_not;
    public $easy_to_use;
    public $easy_to_use_not;
    public $easy_to_follow;
    public $easy_to_follow_not;
    public $dna_sample;
    public $dna_sample_yes;
    public $fingerprinting;
    public $fingerprinting_yes;
    public $kit_design;
    public $kit_design_not;
    public $kit_added;
    public $one_future;
    public $expect_to_find;
    public $most_appealing;
    public $most_appealing_other;
    public $maximum_amount;
    public $maximum_monthly_fee;
    public $for_other_members;
    public $preferred_method;
    public $other_systems;
    public $other_accounts;
    public $consider_for_child;
    public $consider_for_child_other;
    public $level_of_insured;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('ever_purchased, purchase_yourself, purchase_family, easy_to_use,
                  easy_to_follow, dna_sample, fingerprinting, kit_design, kit_added, one_future,
                  expect_to_find, most_appealing, maximum_amount, maximum_monthly_fee,
                  for_other_members, preferred_method, other_systems, other_accounts,
                  consider_for_child, level_of_insured', 'required')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'ever_purchased'            => 'Have you ever purchased a similar product?',
            'ever_purchased_yes'        => 'If yes, how recently?',
            'how_satisfied'             => 'How satisfied were you with the results?',
            'tell_us'                   => 'Tell us more about your experience: (and feel free to share the name of the product as well)',
            'purchase_yourself'         => 'Is this product and identity kit something you would purchase for yourself? ',
            'purchase_yourself_not'     => '(If no, please describe why not)',
            'purchase_family'           => 'Would you consider purchasing a kit for a friend or family member?',
            'purchase_family_not'       => '(If no, please describe why not)',
            'easy_to_use'               => 'Was the kit easy to use?',
            'easy_to_use_not'           => '(If no, please describe why not. Include suggested improvements)',
            'easy_to_follow'            => 'Were directions easy to follow?',
            'easy_to_follow_not'        => '(If no, please describe way not. Include suggested improvements)',
            'dna_sample'                => 'Did you encounter any difficulties collecting and storing the DNA sample?',
            'dna_sample_yes'            => '(If yes, please describe why. Include suggested improvements)',
            'fingerprinting'            => 'Did you encounter any difficulties during the fingerprinting process?',
            'fingerprinting_yes'        => '(If yes, please describe why. Include suggested improvements)',
            'kit_design'                => 'Do you like the way the kit is designed?',
            'kit_design_not'            => ' (If no, what changes would you make?)',
            'kit_added'                 => 'What would you like to change or see added to kit?',
            'one_future'                => 'What is the one feature ChildPass offers that you would make you buy this kit?',
            'expect_to_find'            => 'At what locations would you expect to find a product of this nature?',
            'most_appealing'            => 'Which of the following offers would be the most appealing to: Choose One, or if ‘other’ is selected, please explain:',
            'most_appealing_other'      => 'Other, please explain:',
            'maximum_amount'            => 'What is the maximum amount you would pay for this physical kit in the store (or online) if the online account was free for the first year?',
            'maximum_monthly_fee'       => 'What is the maximum monthly service fee you would be willing to pay for the online service and  Emergency Alert System, if the kit was delivered free of charge?',
            'for_other_members'         => 'Would you be interested in a kit and emergency alert system for other beloved family members? Please choose all that apply:',
            'preferred_method'          => 'What is your preferred method to access the Internet?',
            'other_systems'             => 'Tell us what other family security products you pay for (i.e. ADT, auto security systems, dropcams, etc):',
            'other_accounts'            => 'Tell us what other online accounts you pay for on a monthly basis: (cable TV, satellite radio, etc)',
            'consider_for_child'        => 'Though controversial, the AMA is considering legalizing  the “chipping” of children with microchips for GPS tracking of your children.  Is this something you would consider for your child or a loved one with special needs. Please choose:',
            'consider_for_child_other'  => 'Other - Explain:',
            'level_of_insured'          => 'What level of “insured” would you consider you and your family? (Health, Auto, Life, Homeowner/Renters, Product). Please choose:'
        );
    }

    public function notEmpty($attribute,$params)
    {
        if ($this->$attribute == '0') {
            $this->addError($attribute, 'Question is required!');
        }
    }
}