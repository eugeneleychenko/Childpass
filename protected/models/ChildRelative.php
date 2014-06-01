<?php

/**
 * This is the model class for table "child_relative".
 *
 * The followings are the available columns in table 'child_relative':
 * @property integer $id
 * @property integer $child_id
 * @property integer $relative_id
 * @property integer $relation_id
 *
 * The followings are the available model relations:
 * @property Child $child
 * @property Relation $relation
 * @property Relative $relative
 */
class ChildRelative extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChildRelative the static model class
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
		return 'child_relative';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('child_id, relative_id, relation_id', 'required'),
			array('child_id, relative_id, relation_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, child_id, relative_id, relation_id', 'safe', 'on'=>'search'),
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
			'relation' => array(self::BELONGS_TO, 'Relation', 'relation_id'),
			'relative' => array(self::BELONGS_TO, 'Relative', 'relative_id'),
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
			'relative_id' => 'Relative',
			'relation_id' => 'Relation',
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
		$criteria->compare('relative_id',$this->relative_id);
		$criteria->compare('relation_id',$this->relation_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function removeMapping($childId, $relativeId)
    {
        $childRelativesNumber = count($this->childRelativesMapping($childId));
        if ($childRelativesNumber < 2) {
            return false;
        }

        $criteria = new CDbCriteria();
        $criteria->addCondition('child_id = :child_id AND relative_id = :relative_id');
        $criteria->params = array('child_id' => $childId, 'relative_id' => $relativeId);
        $deleted = $this->deleteAll($criteria);
        if ($deleted) {
            $relativeHasMappings = $this->exists('relative_id = :relative_id', array(':relative_id' => $relativeId));
            if (!$relativeHasMappings) {
                Relative::model()->deleteByPk($relativeId);
            }
        }
        return $deleted;
    }

    public function childRelativesMapping($childId)
    {
        return $this->findAll('child_id = :child_id', array(':child_id' => $childId));
    }

}