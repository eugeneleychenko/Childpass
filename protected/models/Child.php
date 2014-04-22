<?php

/**
 * This is the model class for table "child".
 *
 * The followings are the available columns in table 'child':
 * @property integer $id
 * @property string $created_at
 * @property string $name
 * @property string $gender
 * @property integer $height_feet
 * @property integer $height_inches
 * @property integer $weight
 * @property integer $ethnicity_id
 * @property integer $eyes_color_id
 * @property integer $hair_color_id
 * @property string $address
 * @property string $address2
 * @property string $zip_code
 * @property string $birthday
 * @property string $distinctive_marks
 * @property string $school
 * @property string $school_address
 * @property string $school_address2
 * @property string $school_zip_code
 * @property string $known_relatives
 * @property string $missing_date
 * @property string $missing_from
 *
 * The followings are the available model relations:
 * @property User $user
 * @property HairColor $hairColor
 * @property Ethnicity $ethnicity
 * @property EyesColor $eyesColor
 * @property ChildPhoto[] $childPhotos
 */
class Child extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Child the static model class
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
		return 'child';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, name, gender, ethnicity_id, eyes_color_id, hair_color_id, birthday', 'required'),
			array('user_id, height_feet, height_inches, weight, ethnicity_id, eyes_color_id, hair_color_id', 'numerical', 'integerOnly'=>true),
			array('name, address, address2, school_address, school_address2', 'length', 'max'=>100),
			array('gender', 'length', 'max'=>1),
			array('zip_code, school_zip_code', 'length', 'max'=>10),
			array('distinctive_marks, known_relatives, missing_from', 'length', 'max'=>255),
			array('school', 'length', 'max'=>150),
			array('birthday, missing_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, created_at, name, gender, height_feet, height_inches, weight, ethnicity_id, eyes_color_id, hair_color_id, address, address2, zip_code, birthday, distinctive_marks, school, school_address, school_address2, school_zip_code, known_relatives, missing_date, missing_from', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'hairColor' => array(self::BELONGS_TO, 'HairColor', 'hair_color_id'),
			'ethnicity' => array(self::BELONGS_TO, 'Ethnicity', 'ethnicity_id'),
			'eyesColor' => array(self::BELONGS_TO, 'EyesColor', 'eyes_color_id'),
			'childPhotos' => array(self::HAS_MANY, 'ChildPhoto', 'child_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'user_id' => 'User ID',
			'created_at' => 'Created At',
			'name' => 'Name',
			'gender' => 'Gender',
			'height_feet' => 'Height Feet',
			'height_inches' => 'Height Inches',
			'weight' => 'Weight',
			'ethnicity_id' => 'Ethnicity',
			'eyes_color_id' => 'Eyes Color',
			'hair_color_id' => 'Hair Color',
			'address' => 'Address',
			'address2' => 'Address2',
			'zip_code' => 'Zip Code',
			'birthday' => 'Birthday',
			'distinctive_marks' => 'Distinctive Marks',
			'school' => 'School',
			'school_address' => 'School Address',
			'school_address2' => 'School Address2',
			'school_zip_code' => 'School Zip Code',
			'known_relatives' => 'Known Relatives',
			'missing_date' => 'Missing Date',
			'missing_from' => 'Missing From',
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
        $criteria->compare('user_id',$this->user_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('height_feet',$this->height_feet);
		$criteria->compare('height_inches',$this->height_inches);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('ethnicity_id',$this->ethnicity_id);
		$criteria->compare('eyes_color_id',$this->eyes_color_id);
		$criteria->compare('hair_color_id',$this->hair_color_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('distinctive_marks',$this->distinctive_marks,true);
		$criteria->compare('school',$this->school,true);
		$criteria->compare('school_address',$this->school_address,true);
		$criteria->compare('school_address2',$this->school_address2,true);
		$criteria->compare('school_zip_code',$this->school_zip_code,true);
		$criteria->compare('known_relatives',$this->known_relatives,true);
		$criteria->compare('missing_date',$this->missing_date,true);
		$criteria->compare('missing_from',$this->missing_from,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getGenderOptions()
    {
        return array(
            'M' => 'Male',
            'F' => 'Female',
        );
    }

    public function getAge()
    {
        $date = new DateTime($this->birthday);
        $now = new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }
}