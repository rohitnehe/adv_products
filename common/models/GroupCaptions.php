<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_group_captions".
 *
 * @property int $id
 * @property int $growers_id
 * @property string $caption_name
 * @property string $group_type
 * @property string $description
 * @property int $sort_order
 * @property string $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property int $is_deleted
 */
class GroupCaptions extends \common\models\BaseModel
{
    /**
     * {@inheritdoc}
     */
    const PRODUCT_GROUP = ['Product Group 1'=> 'Product Group 1','Product Group 2'=> 'Product Group 2','Product Group 3'=> 'Product Group 3','Product Group 4'=> 'Product Group 4','Product Group 5'=> 'Product Group 5'];
    const ITEM_GROUP = ['Item Group 1'=> 'Item Group 1','Item Group 2'=> 'Item Group 2','Item Group 3'=> 'Item Group 3','Item Group 4'=> 'Item Group 4','Item Group 5'=> 'Item Group 5'];
    const CAPTION_LEVEL_STATUS = ['A'=>'Active','I'=>'Inactive'];
    const CAPTION_STATUS_ACTIVE = 'A';
    const CAPTION_STATUS_INACTIVE = 'I';
    const CAPTION_STATUS_DELETED = '0';
    const GROUP_TYPE_PRODUCT = 'Product';
    const GROUP_TYPE_ITEM = 'Item';
    const GROUP_TYPE_CUSTOMER = 'Customer';
    const ITEM_GROUP_COLUMN = ['Item Group 1'=> 'itemgroup1','Item Group 2'=> 'itemgroup2','Item Group 3'=> 'itemgroup3','Item Group 4'=> 'itemgroup4','Item Group 5'=> 'itemgroup5'];
    const CUSTOMER_GROUP = ['Customer Group 1'=> 'Customer Group 1','Customer Group 2'=> 'Customer Group 2','Customer Group 3'=> 'Customer Group 3','Customer Group 4'=> 'Customer Group 4'];
    const PRODUCT_GROUP_COLUMN = ['Product Group 1'=> 'productgroup1','Product Group 2'=> 'productgroup2','Product Group 3'=> 'productgroup3','Product Group 4'=> 'productgroup4','Product Group 5'=> 'productgroup5'];

    public static function tableName()
    {
        return '{{%group_captions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caption_name', 'group_type','group_no'], 'required'],
            [['sort_order','created_at','updated_at','is_deleted'], 'integer'],
            [['group_type', 'description', 'status','growers_id','created_by','updated_by'], 'string'],
            [['caption_name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 200],
            [ 'sort_order', 'required', 
                'when' => function ($model) { 
                    return ($model->group_type == "Item"||$model->group_type == "Product"); }, 
              'whenClient' => "function (attribute, value) { return ($('#groupcaptions-group_type').val() == 'Item'||$('#groupcaptions-group_type').val() =='Product'); }" 
            ] 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'growers_id' => 'Growers ID',
            'caption_name' => 'Group Caption Name',
            'group_type' => 'Group Type',
            'group_no' => 'Group Number',
            'description' => 'Description',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GroupCaptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupCaptionsQuery(get_called_class());
    }
    
    /**
     * <@author Nikhil Bhagunde <nikhilbhagunde@benchmarkitsolutions.com>
     * <@Date -: 04-June-2019>
     * @param type $group_no
     * @param type $group_type
     * @return type
     */
    public static function getAttributeValue($group_no, $group_type) {
        
        $grower_id =  Yii::$app->user->identity->id;
        
        #### Added By Prasad : To Get Selected Grower Id ####
        
        $query = self::find()->select('caption_name')->where(['is_deleted' => self::CAPTION_STATUS_DELETED])
                ->andWhere([self::tableName() . '.status' => self::STATUS_ACTIVE])
                ->andWhere([self::tableName() . '.growers_id' => $grower_id])
                ->andWhere([self::tableName() . '.group_no' => $group_no])
                ->andWhere([self::tableName() . '.group_type' => $group_type])
                ->asArray()
                ->one();
        if(empty($query))
        {
            return NULL;
        }
        return $query;
    }
}
