<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $created_at
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property integer $is_active
 * @property string $verification_code
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email, name', 'required'),
            array('username, email', 'unique'),
			array('email', 'email'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('username, email', 'length', 'max'=>50),
			array('password, verification_code', 'length', 'max'=>64),
            array('password', 'length', 'min'=> 6),
			array('name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created_at, username, password, email, name, is_active, verification_code', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
        return array(
            'id'                => 'ID',
            'created_at'        => 'Created At',
            'username'          => 'Username',
            'password'          => 'Password',
            'email'             => 'Email',
            'name'              => 'Name',
            'is_active'         => 'Is Active',
            'verification_code' => 'Verification Code',
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
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('verification_code',$this->verification_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function hashPassword($password)
    {
        return hash('sha256', $password);
    }

    public function generatePasswordResetLink()
    {
        $code = sha1(($this->id * 1000 / 434) . '_' . time() . '_' . rand(0, 10000));
        $this->updateByPk($this->id, array('password_reset_code' => $code));

        return Yii::app()->createAbsoluteUrl('user/password-reset', array('code' => $code));
    }
}