<?php

/**
 * This is the model class for table "incident".
 *
 * The followings are the available columns in table 'incident':
 * @property integer $id
 * @property integer $child_id
 * @property string $child_description
 * @property string $description
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Child $child
 */
class Incident extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Incident the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'incident';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('child_id, child_description, description, date', 'required'),
			array('child_id', 'numerical', 'integerOnly'=>true),
            array('child_id', 'unique'),
			array('child_description, description', 'length', 'max'=>255),
            array('date', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss', 'message'=>'{attribute} has wrong format. Format should be: yyyy-mm-dd hh:mm:ss.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, child_id, child_description, description, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'child' => array(self::BELONGS_TO, 'Child', 'child_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'child_id' => 'Child',
			'child_description' => 'Child Description',
			'description' => 'Describe the incident',
			'date' => 'Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('child_id',$this->child_id);
		$criteria->compare('child_description',$this->child_description,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}