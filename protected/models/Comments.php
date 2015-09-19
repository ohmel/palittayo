<?php

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property integer $comment_id
 * @property string $comment
 * @property integer $user_id
 * @property integer $item_id
 * @property date $date_posted
 */
class Comments extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Comments the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('comment, user_id, item_id, date_posted', 'required'),
            array('user_id, item_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('comment_id, comment, user_id, item_id, date_posted', 'safe', 'on' => 'search'),
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
            'poster' => array(self::BELONGS_TO, 'Users', 'poster_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'comment_id' => 'Comment',
            'comment' => 'Comment',
            'user_id' => 'User',
            'item_id' => 'Item',
            'date_posted' => 'Date Posted',
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

        $criteria = new CDbCriteria;

        $criteria->compare('comment_id', $this->comment_id);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('date_posted', $this->date_posted);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function checkCommentType($commentType){
        $commentTypes = array('user'=>'user_id', 'item'=>'item_id');
        if($commentTypes[$commentType] != ""){
            return $commentTypes[$commentType];
        }else{
            return false;
        }
    }

    public function getComments($id, $commentType)
    {
        $commentArr = array();
        $criteria = new CDbCriteria;
        $commentType = $this->checkCommentType($commentType);
        if($commentType !== false){
            if($commentType == "user_id"){
                $criteria->compare('t.user_id', $id);
            }
            if($commentType == "item_id"){
                $criteria->compare('t.item_id', $id);
            }
        }
		$comments = $this::model()->with('poster')->findAll($criteria);
        if($comments){
            $index = 0;
            foreach($comments as $comment){
                $commentArr[$index]['comment'] = $comment->attributes;
                $commentArr[$index]['comment']['poster']['user_id'] = $comment->poster->user_id;
                $commentArr[$index]['comment']['poster']['user_fullname'] = $comment->poster->user_fullname;
                $index++;
            }
            return $commentArr;
        }else{
            return null;
        }
	}
}