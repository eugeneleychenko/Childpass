<?php

/**
 * This is the model class for table "child_photo".
 *
 * The followings are the available columns in table 'child_photo':
 * @property integer $id
 * @property string $created_at
 * @property integer $child_id
 * @property string $filename
 * @property string $is_main
 * The followings are the available model relations:
 * @property Child $child
 */
class ChildPhoto extends CActiveRecord
{
    public $image;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChildPhoto the static model class
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
		return 'child_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('child_id, filename', 'required'),
			array('child_id, is_main', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>100),
            array('image', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize' => 10485760),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created_at, child_id, filename, is_main', 'safe', 'on'=>'search'),
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
			'created_at' => 'Created At',
			'child_id' => 'Child',
			'filename' => 'Filename',
            'is_main' => 'Is Main',
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
		$criteria->compare('child_id',$this->child_id);
		$criteria->compare('filename',$this->filename,true);
        $criteria->compare('is_main',$this->is_main);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getUrl() {
        return Yii::app()->request->baseUrl . "/children/{$this->child_id}/photos/" . $this->filename;
    }
}