<?php

/**
 * This is the model class for table "follow".
 *
 * The followings are the available columns in table 'follow':
 * @property integer $follow_id
 * @property integer $following_id
 * @property integer $follower_id
 */
class Follow extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Follow the static model class
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
		return 'follow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('following_id, follower_id', 'required'),
			array('following_id, follower_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('follow_id, following_id, follower_id', 'safe', 'on'=>'search'),
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
			'follow_id' => 'Follow',
			'following_id' => 'Following',
			'follower_id' => 'Follower',
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

		$criteria->compare('follow_id',$this->follow_id);
		$criteria->compare('following_id',$this->following_id);
		$criteria->compare('follower_id',$this->follower_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}