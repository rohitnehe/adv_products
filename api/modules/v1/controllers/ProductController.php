<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class ProductController extends ActiveController {
    
     public $modelClass = 'common\models\Products';
    
     public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => [],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['index'],
            'rules' => [
                [                    
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }
    
    /**
     * 
     * @return type
     */
    public function actions() {
        $actions = parent::actions();
        unset($actions['index'], $actions['view']);
        return $actions;
    }  
    
    /**
     * @author Kumar Waghmode <kumarwaghmode@benchmarkitsolutions.com>
     * @date : 04-June-2019
     * This Action is Used To return list of all products associated with logged in grower. 
     */
    public function actionIndex() {
        $cur_growers_id = \CHelper::getUserIdentityData('id');

        $modelClass = $this->modelClass;
        $data = $modelClass::find()->Where(['=', 'growers_id', $cur_growers_id])->andWhere(['!=', 'growpoint_external_id', ''])->andWhere(['!=', 'growpoint_external_id', '0'])->asArray()->all();        
        if(!empty($data)){
            $response_fields=array('external_id','botanical_name','common_name','other_name','productgroup1','productgroup2','productgroup3','productgroup4','productgroup5'); 
            $data=  \RestHelper::apiResponseFeildsArr($data,$response_fields);
            return \RestHelper::formatResponseSuccess('2d513df5-16a7-4442-a1b4-2373d5c12967', $data);
        }else{ 
            return \RestHelper::formatResponseError('afd55005-6305-402d-a1b2-049b1c71ae2c', $data);
        }
    }
    /**
     * End 
     **/
    
    /**
     * @author Kumar Waghmode <kumarwaghmode@benchmarkitsolutions.com>
     * @date : 04-June-2019
     * This Action is Used To return products with given product external id
     */
    public function actionView($id=NULL) {
        $cur_growers_id = \CHelper::getUserIdentityData('id');          
        $modelClass = $this->modelClass;
        $data = $modelClass::find()->Where(['=', 'growers_id', $cur_growers_id])->andWhere(['=', 'growpoint_external_id', $id])->asArray()->all();  //id is growpoint_external_id for  product
        if(!empty($data)){
            $response_fields=array('external_id','botanical_name','common_name','other_name','productgroup1','productgroup2','productgroup3','productgroup4','productgroup5'); 
            $data=  \RestHelper::apiResponseFeildsArr($data,$response_fields);
            return \RestHelper::formatResponseSuccess('6e922e50-cd09-43d1-8153-051004a708d4', $data);
        }else{            
             return \RestHelper::formatResponseError('cb7d9164-9ac0-4390-a3c7-2a6ff63171c5', $data);
        }
    }
    /**
    * End 
    **/
}

