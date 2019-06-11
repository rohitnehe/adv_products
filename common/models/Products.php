<?php

namespace common\models;

use common\models\GroupCaptions;
/**
 * This is the model class for table "{{%products}}".
 *
 * @property string $id
 * @property string $growers_id
 * @property string $growpoint_external_id
 * @property string $botanical_name Full Botancial name
 * @property string $common_name Common Name
 * @property string $other_name Grower Choice
 * @property string $productgroup1 Grower Chosen Stat Groups
 * @property string $productgroup2 Grower Chosen Stat Groups
 * @property string $productgroup3 Grower Chosen Stat Groups
 * @property string $productgroup4 Grower Chosen Stat Groups
 * @property string $productgroup5 Grower Chosen Stat Groups
 * @property string $genus Static for AGS Analytics
 * @property string $zone Static for AGS Analytics
 * @property string $type Static for AGS Analytics
 * @property string $color Static for AGS Analytics
 * @property string $status
 * @property string $created_by Primary key of tbl_users
 * @property int $created_at
 * @property string $updated_by Primary key of tbl_users
 * @property int $updated_at
 * @property int $is_deleted
 */
class Products extends \common\models\BaseModel
{
    const PRODUCT_CRUD = 'productCrud';
    const DEFAULT_GROWPOINT_EXTERNAL_ID = '0';
    public $item_name;
    const PRODUCT_GROUP_COLUMN = ['Product Group 1'=> 'productgroup1','Product Group 2'=> 'productgroup2','Product Group 3'=> 'productgroup3','Product Group 4'=> 'productgroup4','Product Group 5'=> 'productgroup5'];
    const IS_DELETED ='0';
    const IS_ACTIVE ='A';
    public $preorder_no;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['botanical_name'], 'required', 'on' => self::PRODUCT_CRUD],
            [['botanical_name', 'common_name', 'other_name', 'productgroup1', 'productgroup2', 'productgroup3', 'productgroup4', 'productgroup5'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['status'], 'string'],
            [['item_name'], 'safe'],
            [['preorder_no'], 'safe'],
            [['created_at', 'updated_at', 'is_deleted'], 'integer'],
            [['id', 'growers_id', 'created_by', 'updated_by'], 'string', 'max' => 50],
            [['growpoint_external_id'], 'string', 'max' => 55],
            [['productgroup1', 'productgroup2', 'productgroup3', 'productgroup4', 'productgroup5'], 'string','max' => 50, 'on' => self::PRODUCT_CRUD],
            [['genus', 'zone', 'type', 'color'], 'string', 'max' => 255],
            [['botanical_name', 'common_name', 'other_name'], 'string', 'max' => 100, 'on' => self::PRODUCT_CRUD],
            [['id'], 'unique'],
            [['productgroup1', 'productgroup2', 'productgroup3', 'productgroup4', 'productgroup5'], 'match', 'pattern' => "/^[':;A-Za-z 0-9_ &.\-\~`!@#$%^*=(){}<>|?,]+$/i"],
            
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
            'growpoint_external_id' => 'Growpoint External ID',
            'botanical_name' => 'Botanical Name',
            'common_name' => 'Common Name',
            'other_name' => 'Other Name',
            'productgroup1' => !empty(GroupCaptions::getAttributeValue('Product Group 1', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name']) ? GroupCaptions::getAttributeValue('Product Group 1', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name'] : 'Product Group 1',
            'productgroup2' => !empty(GroupCaptions::getAttributeValue('Product Group 2', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name']) ? GroupCaptions::getAttributeValue('Product Group 2', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name'] : 'Product Group 2',
            'productgroup3' => !empty(GroupCaptions::getAttributeValue('Product Group 3', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name']) ? GroupCaptions::getAttributeValue('Product Group 3', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name'] : 'Product Group 3',
            'productgroup4' => !empty(GroupCaptions::getAttributeValue('Product Group 4', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name']) ? GroupCaptions::getAttributeValue('Product Group 4', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name'] : 'Product Group 4',
            'productgroup5' => !empty(GroupCaptions::getAttributeValue('Product Group 5', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name']) ? GroupCaptions::getAttributeValue('Product Group 5', GroupCaptions::GROUP_TYPE_PRODUCT)['caption_name'] : 'Product Group 5',
            'genus' => 'Static for AGS Analytics',
            'zone' => 'Static for AGS Analytics',
            'type' => 'Static for AGS Analytics',
            'color' => 'Static for AGS Analytics',
            'status' => 'Status',
            'created_by' => 'Primary key of tbl_users',
            'created_at' => 'Created At',
            'updated_by' => 'Primary key of tbl_users',
            'updated_at' => 'Updated At',
            'is_deleted' => 'Is Deleted',
          //  'preorder_no' => 'preorder_no',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }
    
    /**
     * Created By: Prasad Bhale
     * @desc get Product and Item relation
     */
    public function getItems() {
        return $this->hasOne(Items::className(), ['products_id' => 'id']);
    }
    
    /**
     * <@author Nikhil Bhagunde <nikhilbhagunde@benchmarkitsolutions.com>
     * <@Date -: 22-Jan-2019>
     * <@Description -: To, get product list in '$key => $value' Pair.>
     * @return type
     */
    public static function productListWithId() {
        return yii\helpers\ArrayHelper::map(self::find()->select(['id','botanical_name'])->where([self::tableName().'.is_deleted' => self::STATUS_NOT_DELETED, self::tableName() . '.growers_id' => \CHelper::getCurrentGrowerId()])->orderBy('botanical_name')->all(), 'id','botanical_name');
    }
    
}