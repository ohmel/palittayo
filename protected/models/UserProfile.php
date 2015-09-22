<?php

/**
 * This is the model class for table "user_profile".
 *
 * The followings are the available columns in table 'user_profile':
 * @property integer $profile_id
 * @property integer $user_id
 * @property string $profile_fname
 * @property string $profile_lname
 * @property string $profile_mname
 * @property string $profile_mobile
 * @property string $profile_tel
 * @property string $profile_location
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserProfile extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserProfile the static model class
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
		return 'user_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, profile_fname, profile_lname, profile_mobile', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('profile_fname, profile_lname, profile_mname', 'length', 'max'=>50),
			array('profile_mobile, profile_tel', 'length', 'max'=>20),
			array('profile_location', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('profile_id, user_id, profile_fname, profile_lname, profile_mname, profile_mobile, profile_tel, profile_location', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'profile_id' => 'Profile',
			'user_id' => 'User',
			'profile_fname' => 'Profile Fname',
			'profile_lname' => 'Profile Lname',
			'profile_mname' => 'Profile Mname',
			'profile_mobile' => 'Profile Mobile',
			'profile_tel' => 'Profile Tel',
			'profile_location' => 'Profile Location',
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

		$criteria->compare('profile_id',$this->profile_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('profile_fname',$this->profile_fname,true);
		$criteria->compare('profile_lname',$this->profile_lname,true);
		$criteria->compare('profile_mname',$this->profile_mname,true);
		$criteria->compare('profile_mobile',$this->profile_mobile,true);
		$criteria->compare('profile_tel',$this->profile_tel,true);
		$criteria->compare('profile_location',$this->profile_location,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getProfile($userId){
		$profile = $this::model()->find("user_id = {$userId}");
		if($profile){
			return $profile->attributes;
		}else{
			throw new CHttpException(404,'The specified user cannot be found.');
		}
	}

	public function checkIfFollowing($followerId, $userId){
		$criteria = $criteria=new CDbCriteria;
		$criteria->compare('follower_id',$followerId);
		$criteria->compare('user_id',$userId);

		$following = Follow::model()->find($criteria);
		if($following){
			return true;
		}else{
			return false;
		}
	}

	public function follow($followerId, $userId){
		$profile = $this::model()->findByPk($userId);
		$profile->follower = $profile->follower + 1;
		if(!$profile->save()){
			throw new CHttpException(500, "There might have been an error following this account...");
		}else{
			$followTable = new Follow();
			$followTable->follower_id = $followerId;
			$followTable->following_id = $userId;
			if(!$followTable->save()){
				throw new CHttpException(500, "There might have been an error following this account...");
			}else{
				return true;
			}
		}
	}
}