<?php

namespace backend\controllers;

use Yii;
use app\models\Customers;
use app\models\CustomersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use borales\extensions\phoneInput\PhoneInputBehavior;
use yii\helpers\Json;
use app\models\Patches\Patch;
use yii\filters\AccessControl;
use app\models\MailingList;
use yii\helpers\ArrayHelper;
use app\models\Model;

/**
 * CustomersController implements the CRUD actions for Customers model.
 */
class CustomersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
             'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],

                // ...
            ],
        ],
        ];
    }

    /**
     * Lists all Customers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         if (Yii::$app->request->post('hasEditable')) {
          $id=$_POST['editableKey'];
          $model = Customers::findOne($id);
          $out = Json::encode(['output'=>'', 'message'=>'']);
          $post = [];
          $redirectFlag = false;
          $posted = current($_POST['Customers']);
          $post['Customers'] = $posted;
          if ($model->load($post)) {
            date_default_timezone_set("Africa/Nairobi");
            $model->updated_by  = \Yii::$app->user->identity->id;
            $model->updated_at = date('Y:m:d H:i:s');
           $model->save(false);
                $output ='me';
                 $out = Json::encode(['output'=>'', 'message'=>'']);
            // \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
                   Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Status Changed successfully.']);
             }
          
          echo $out;
        return $this->redirect(['index']);
          
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customers model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customers();
        $modelsMail = [new MailingList];

         if ($model->load(Yii::$app->request->post())) {
            $modelsMail = Model::createMultiple(MailingList::classname());
            Model::loadMultiple($modelsMail, Yii::$app->request->post());
  
            date_default_timezone_set("Africa/Nairobi");
            $model->customer_id = Patch::randomNumbers();
            $model->created_at = date('Y:m:d H:i:s');
            $model->created_by = \Yii::$app->user->identity->id;
            $model->status = 10;
           
            // $valid = $model->validate();
            // $valid = Model::validateMultiple($modelsPackageRoutes) && $valid;

            
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if($flag = $model->save(false)){
                      
                        foreach ($modelsMail as $modelsMails) {
                            if(empty($modelsMails->email)){

                            }else{
                            $modelsMails->customer_id = $model->customer_id;
                            if(! ($flag = $modelsMails->save(false))){
                                $transaction->rollBack();
                                break;
                            }
                        }
                            # code...
                        }
                    }
                    if($flag){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Customer created successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                }
                catch (Exception $e){
                    $transaction->rollBack();
                }
            
        }
        return $this->renderAjax('create', [
            'model' => $model,
            'modelsMail'=>(empty($modelsMail)) ? [new MailingList] : $modelsMail
        ]);
    

    }

    /**
     * Updates an existing Customers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsMail = $model->subCategory;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsMail, 'id', 'id');
            $modelsMail = Model::createMultiple(MailingList::classname(), $modelsMail);
            Model::loadMultiple($modelsMail, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsMail, 'id', 'id')));

            //$model->menu_active_department = implode(',', $model->menu_active_department);
            

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            MailingList::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($modelsMail as $modelsMails) {
                           
                             $modelsMails->customer_id = $model->customer_id;
                            if (! ($flag = $modelsMails->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                    }
                    
                }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Update successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'modelsMail'=>(empty($modelsMail)) ? [new MailingList] : $modelsMail
            ]);
    }

    /**
     * Deletes an existing Customers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Customers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
