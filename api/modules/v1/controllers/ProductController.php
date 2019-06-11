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
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
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
        unset($actions['index'], $actions['view'], $actions['create']);
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
     * @author Kumar Waghmode <kumarwaghmode@benchmarkitsolutions.com>
     * @date : 10 Dec. 2018
     * Purpose: This Acion is Used To create & update products and return list of created, updated & failure products to api.
     */
    public function actionCreate() {
        $cur_growers_id = \CHelper::getUserIdentityData('id');
        $cur_growers_data = \CHelper::getUserIdentityData();
        $modelClass = $this->modelClass;
        $model = new $modelClass;
        $data = array();
        $params = Yii::$app->request->bodyParams;

        $productModel = $modelClass::find()->where(['=', 'growers_id', $cur_growers_id])->andWhere(['=', 'growpoint_external_id', $params['external_id']])->one();

        if (!empty($params) && empty($productModel)) {
            $model->growers_id = $cur_growers_id;
            $model->growpoint_external_id = $params['external_id'];
            $model->botanical_name = $params['botanical_name'];
            $model->common_name = $params['common_name'];
            $model->other_name = $params['other_name'];
            $model->productgroup1 = $params['productgroup1'];
            $model->productgroup2 = $params['productgroup2'];
            $model->productgroup3 = $params['productgroup3'];
            $model->productgroup4 = $params['productgroup4'];
            $model->productgroup5 = $params['productgroup5'];
            if ($model->save()) {
                $data['module'] = "Product";
                $data['log_type'] = "Created";
                $data['message'] = "Product created successfully";
                return \RestHelper::formatResponseSuccess('6e922e50-cd09-43d1-8153-051004a708d4', $data);
            } else {
                return \RestHelper::formatResponseError('cb7d9164-9ac0-4390-a3c7-2a6ff63171c5', $data);
            }
        } else {
            if (!empty($params)) {
                $productModel->botanical_name = $params['botanical_name'];
                $productModel->common_name = $params['common_name'];
                $productModel->other_name = $params['other_name'];
                $productModel->productgroup1 = $params['productgroup1'];
                $productModel->productgroup2 = $params['productgroup2'];
                $productModel->productgroup3 = $params['productgroup3'];
                $productModel->productgroup4 = $params['productgroup4'];
                $productModel->productgroup5 = $params['productgroup5'];
                if ($productModel->save()) {
                    $data['module'] = "Product";
                    $data['log_type'] = "Updated";
                    $data['message'] = "Product updated successfully";
                    return \RestHelper::formatResponseSuccess('6e922e50-cd09-43d1-8153-051004a708d4', $data);
                }
            }
        }
        return \RestHelper::formatResponseError('cb7d9164-9ac0-4390-a3c7-2a6ff63171c5', $data);
    }

    /**
    * End 
    **/
}

