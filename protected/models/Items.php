<?php

/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property integer $item_id
 * @property string $item_name
 * @property string $item_desc
 * @property string $item_trade_value
 * @property integer $category_id
 * @property string $item_condition
 * @property integer $status
 * @property integer $featured
 * @property integer $user_id
 * @property string $date_posted
 * @property string $date_updated
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Users $user
 */
class Items extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Items the static model class
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
        return 'items';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_name, item_desc, item_trade_value, category_id, item_condition, status, featured, user_id, date_posted, date_updated', 'required'),
            array('category_id, status, featured, user_id', 'numerical', 'integerOnly' => true),
            array('item_name', 'length', 'max' => 100),
            array('item_trade_value, item_condition', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('item_id, item_name, item_desc, item_trade_value, category_id, item_condition, status, featured, user_id, date_posted, date_updated', 'safe', 'on' => 'search'),
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
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'item_id' => 'Item',
            'item_name' => 'Item Name',
            'item_desc' => 'Item Desc',
            'item_trade_value' => 'Item Trade Value',
            'category_id' => 'Category',
            'item_condition' => 'Item Condition',
            'status' => 'Status',
            'featured' => 'Featured',
            'user_id' => 'User',
            'date_posted' => 'Date Posted',
            'date_updated' => 'Date Updated',
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

        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('item_name', $this->item_name, true);
        $criteria->compare('item_desc', $this->item_desc, true);
        $criteria->compare('item_trade_value', $this->item_trade_value, true);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('item_condition', $this->item_condition, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('featured', $this->featured);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('date_posted', $this->date_posted, true);
        $criteria->compare('date_updated', $this->date_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Retrieves all Items inside the Database
     * @return Array of all Items retrieved
     *
     */
    public function getItemListArr()
    {
        $items = $this::model()->with('user')->recently()->findAll();
        $itemArr = array();
        $index = 0;
        foreach ($items as $item) {
            $itemArr[$index] = $item->attributes;
            $itemArr[$index]['user']['user_id'] = $item->user->user_id;
            $itemArr[$index]['user']['user_fullname'] = $item->user->user_fullname;
            $itemArr[$index]['user']['user_type'] = $item->user->user_type;
            $itemArr[$index]['user']['status'] = $item->user->status;
            $index++;
        }
        return $itemArr;
    }

    public function getItemOne($itemId)
    {
        $item = $this->model()->with('user')->findByPk($itemId);
        $itemArr = array();
        $itemArr = $item->attributes;
        $itemArr['user'] = $item->user->attributes;
        return $itemArr;
    }

    public function getUserItems($userId){
        $items = $this::model()->findAll("user_id = {$userId}");
        $itemArr = array();
        $index = 0;
        foreach ($items as $item) {
            $itemArr[$index] = $item->attributes;
            $itemArr[$index]['user']['user_id'] = $item->user->user_id;
            $itemArr[$index]['user']['user_fullname'] = $item->user->user_fullname;
            $itemArr[$index]['user']['user_type'] = $item->user->user_type;
            $itemArr[$index]['user']['status'] = $item->user->status;
            $index++;
        }
        return $itemArr;
    }

    public function scopes()
    {
        return array(
            'published' => array(
                'condition' => 'featured=2',
            ),
            'recently' => array(
                'order' => 'date_updated DESC',
                'limit' => 8,
            ),
        );
    }
}