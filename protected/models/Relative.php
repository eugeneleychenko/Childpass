<?php

/**
 * This is the model class for table "relative".
 *
 * The followings are the available columns in table 'relative':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 *
 * The followings are the available model relations:
 * @property ChildRelative[] $childRelatives
 */
class Relative extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Relative the static model class
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
		return 'relative';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name', 'required'),
			array('first_name, last_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, last_name', 'safe', 'on'=>'search'),
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
//			'childRelatives' => array(self::HAS_MANY, 'ChildRelative', 'relative_id'),
            'children' => array(self::MANY_MANY, 'Children', 'child_relative(relative_id, child_id)'),
            'childRelative' => array(self::HAS_MANY, 'ChildRelative', 'relation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function saveRelatives($childId, $relatives, $userId)
    {
        foreach ($relatives as $relative) {
            if (!isset($relative['first_name']) || !isset($relative['first_name']) || !isset($relative['relation_id'])) {
                continue;
            }

            if (!isset($relative['child_relative_id'])) {
                if (!isset($relative['relative_id'])) {
                    $relativeModel = $this->saveRelative($relative['first_name'], $relative['last_name']);
                    $relativeId = $relativeModel->primaryKey;
                    $childRelativeModel = ChildRelative::model()->saveMapping($childId, $relativeId,
                                                                                $relative['relation_id']);
                } else {
                    if (!$this->relativeBelongsToUser($relative['relative_id'], $userId)) {
                        continue;
                    }
                    $relativeModel = $this->saveRelative($relative['first_name'], $relative['last_name'],
                                                                                 $relative['relative_id']);
                    $childRelativeModel = ChildRelative::model()->saveMapping($childId, $relative['relative_id'],
                        $relative['relation_id']);
                }
            } else {
                if (!isset($relative['relative_id'])) {
                    continue;
                }

                if (!$this->relativeBelongsToUser($relative['relative_id'], $userId)) {
                    continue;
                }

                $relativeModel = $this->saveRelative($relative['first_name'], $relative['last_name'],
                                                                                 $relative['relative_id']);
                $relativeId = $relativeModel->primaryKey;
                $childRelativeModel = ChildRelative::model()->saveMapping($childId, $relativeId,
                                                              $relative['relation_id'], $relative['child_relative_id'], $userId);
            }
        }
    }

    public function relativeBelongsToUser($relativeId, $userId)
    {
        $relativeMapping = ChildRelative::model()->with('child')->find('relative_id = :relative_id', array(':relative_id' => $relativeId));
        if (!$relativeMapping) {
            return false;
        }

        return ($relativeMapping->child->user_id == $userId);
    }



    public function saveRelative($firstName, $lastName, $relativeId = null)
    {
        if ($relativeId) {
            $relativeModel = self::model()->findByPk($relativeId);
        } else {
            $relativeModel = new self;
        }
        $relativeModel->first_name = $firstName;
        $relativeModel->last_name = $lastName;
        $relativeModel->save();
        return $relativeModel;
    }


}