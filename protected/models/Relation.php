<?php

/**
 * This is the model class for table "relation".
 *
 * The followings are the available columns in table 'relation':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ChildRelative[] $childRelatives
 */
class Relation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Relation the static model class
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
		return 'relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'childRelatives' => array(self::HAS_MANY, 'ChildRelative', 'relation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * @return array ethnicity's options
     */
    public function getOptions()
    {
        $rows = $this->findAll();
        $options = array();
//        foreach ($rows as $row) {
//            $options[] = array(
//                'id' => $row->primaryKey,
//                'name' => $row->name
//            );
//        }

        $options = array();
        foreach ($rows as $row) {
            $options[$row->id] = $row->name;
        }
        return $options;
    }

}