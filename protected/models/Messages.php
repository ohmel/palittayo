<?php

/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property integer $message_id
 * @property integer $sender_id
 * @property integer $user_id
 * @property string $date_posted
 * @property string $message_subject
 * @property string $message_body
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users $sender
 */
class Messages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
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
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender_id, user_id, date_posted, message_subject, message_body', 'required'),
			array('sender_id, user_id', 'numerical', 'integerOnly'=>true),
			array('message_subject', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('message_id, sender_id, user_id, date_posted, message_subject, message_body', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'sender' => array(self::BELONGS_TO, 'Users', 'sender_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'message_id' => 'Message',
			'sender_id' => 'Sender',
			'user_id' => 'User',
			'date_posted' => 'Date Posted',
			'message_subject' => 'Message Subject',
			'message_body' => 'Message Body',
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

		$criteria->compare('message_id',$this->message_id);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date_posted',$this->date_posted,true);
		$criteria->compare('message_subject',$this->message_subject,true);
		$criteria->compare('message_body',$this->message_body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}