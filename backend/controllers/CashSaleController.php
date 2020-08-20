<?php

namespace backend\controllers;

use Yii;
use app\models\CashSale;
use app\models\CashSaleSub;
use app\models\CashSaleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JobCard;
use app\models\JobCardSub;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\Model;
use app\models\Sales;
use yii\filters\AccessControl;

/**
 * CashSaleController implements the CRUD actions for CashSale model.
 */
class CashSaleController extends Controller
{

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
     * Lists all CashSale models.
     * @return mixed
     */
    

    public function actionIndex()
    {
        $searchModel = new CashSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['office'=> \Yii::$app->user->identity->office_location])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CashSale model.
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
     * Creates a new CashSale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */




    public function actionCreate()
    {
        $model = new CashSale();
        $modelsSubcategory = [new CashSaleSub];
        
        if ($model->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(CashSale::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());

            // if(isset($_POST['cost'])){
            //     $cost = $_POST['cost'];
            // }    

            $models = CashSale::find()->all();
            $modelJob = CashSale::find()->all();
            $number_array = count($modelJob);
            $job_card_num = $number_array + 1;
            $deposit = 0;


            $modelCustomer = Customers::find()->where(['customer_id'=>$model->customer_id])->all();
            foreach ($modelCustomer as $value) {
                // $customer_name = $value['customer_names'];
                $phone_num = $value['customer_phone'];
            
        }           
           $costs = floatval(preg_replace('/[^\d.]/', '', $model->cost));
           $deposit = floatval(preg_replace('/[^\d.]/', '', $model->deposit_paid));
           $balance = floatval(preg_replace('/[^\d.]/', '', $model->balance));
           // $balance = $costs - $deposit;
           date_default_timezone_set("Africa/Nairobi"); 


            if($model->mode_of_payment == 'cash_sale'){
              $model->status = 13;  
               $model->job_card_number = sprintf('CH'.'%04d', $job_card_num);
            }else if($model->mode_of_payment == 'profoma_invoice'){
                $model->status = 11;
                $model->job_card_number = sprintf('INV'.'%04d', $job_card_num);
            }
           // $model->job_card_number = $job_card_number;
            // $model->customer_name = $customer_name;
            $model->cost = number_format($costs);
            $model->deposit_paid = number_format($deposit);

            $model->balance = number_format($balance);
            $model->created_at = date('Y:m:d H:i:s');
            $model->created_by = \Yii::$app->user->identity->id;
            //$model->status = 11;

             $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if($flag = $model->save(false)){
                      
                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            if(empty($modelsSubcategorys->subcategory)){
                            $modelsSubcategorys->job_card_id = $model->id;
                            if(! ($flag = $modelsSubcategorys->save(false))){
                                $transaction->rollBack();
                                break;
                            }
                        }
                            # code...
                        }
                    }
                    if($flag){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                }
                catch (Exception $e){
                    $transaction->rollBack();
                }
            
        }
        return $this->render('create', [
            'model' => $model,
            'modelsSubcategory'=>(empty($modelsSubcategory))? [new CashSaleSub] : $modelsSubcategory
        ]);
    }

 
    /**
     * Updates an existing CashSale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsSubcategory = $model->subCategorycash;

        if ($model->load(Yii::$app->request->post())) {
         $oldIDs = ArrayHelper::map($modelsSubcategory, 'id', 'id');
            $modelsSubcategory = Model::createMultiple(CashSaleSub::classname(), $modelsSubcategory);
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSubcategory, 'id', 'id')));

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            CashSaleSub::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            if(!empty($modelsSubcategorys->subcategory)){
                             $modelsSubcategorys->job_card_number = $model->job_card_number;
                            if (! ($flag = $modelsSubcategorys->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
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


        return $this->render('update', [
            'model' => $model,
             'modelsSubcategory' => (empty($modelsSubcategory)) ? [new CashSaleSub] : $modelsSubcategory
        ]);
    }

    //payments

    public function actionPayments($id){
        $model = $this->findModel($id);
        $modelSale = new Sales;

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('payments', [
                    'model' => $model,
                   'modelSale'=>$modelSale,
                ]);
            } else {
                return $this->renderAjax('payments', [
                    'model' => $model,
                    'modelSale'=>$modelSale,
                ]);
            }

    }


    public function actionMakepayment(){
       
         if(isset($_POST['id'])){
          $modelSale = new Sales;
          date_default_timezone_set("Africa/Nairobi");
          $customer_id = $_POST['customer_id'];
          $total_amount = $_POST['total_amount'];
          $balance = floatval(preg_replace('/[^\d.]/', '', $_POST['balance']));
          $payment = floatval(preg_replace('/[^\d.]/', '', $_POST['payment']));
          $transaction_number = $_POST['transaction_number'];
          $id = $_POST['id'];
          $modelJobCard = CashSale::find()->where(['id'=>$id])->all();
          foreach ($modelJobCard as  $value) {
              $job_card_number = $value['job_card_number'];
              $cost = floatval(preg_replace('/[^\d.]/', '', $value['cost']));
              $sales_tax = floatval(preg_replace('/[^\d.]/', '', $value['sales_tax']));
          }

          $modelSale->job_card_number = $job_card_number;
          $modelSale->cash_sale_number = $id;
          $modelSale->customer_id = $customer_id;
          $modelSale->payment = $payment;
          $modelSale->created_at = date('Y:m:d H:i:s');
          $modelSale->created_by = \Yii::$app->user->identity->id;
          $modelSale->transaction_number = $transaction_number;
          $newbalance = $balance - $payment;
          $modelSale->cost = number_format($cost);
          $modelSale->total_amount = $total_amount;
          $modelSale->sales_tax = $sales_tax;
          $modelSale->balance = $newbalance;
          $modelSale->save(false);

           $sum_payment = 0;
          $modelSaleBalance = Sales::find()->where(['job_card_number'=>$job_card_number])->all();
          foreach ($modelSaleBalance as  $value) {
              $payments_all = $value['payment'];

          
          $total =array($payments_all); 

          for ($i=0; $i <count($total); $i++) { 
              $sum_payment += $payments_all;
          
          }
      }
      

           Yii::$app->db->createCommand()

            ->update('table_cash_sale', ['payment'=>number_format($sum_payment),'balance'=>number_format($newbalance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $id])

                 ->execute(false);


           Yii::$app->db->createCommand()

            ->update('table_job_card', ['payment'=>number_format($sum_payment),'balance'=>number_format($newbalance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_number])

                 ->execute(false);
                 
                  return $this->redirect(['index']); 
                    }

                               
                }

    /**
     * Deletes an existing CashSale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPrint() {

        //return $this->render('print');
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelCashSale = CashSale::find()->where(['id'=>$id])->all();
            $modelCashSaleSub = CashSaleSub::find()->where(['cash_sale_id'=>$id])->all();
            $modelSales = Sales::find()->where(['cash_sale_number'=>$id])->orderBy(['id'=>SORT_DESC])->one();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub, 'modelSales'=>$modelSales]),
       // 'content' => $this->renderPartial('createprint'),
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            'SetTitle' => 'Receipt',
        ]
    ]);
    return $pdf->render('createprint', [
            'modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub,
            'modelSales'=>$modelSales
           
        ]);
}
      
}
   


    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelCashSale = CashSale::find()->where(['id'=>$id])->all();
            $modelCashSaleSub = CashSaleSub::find()->where(['cash_sale_id'=>$id])->all();
            $modelSales = Sales::find()->where(['cash_sale_number'=>$id])->orderBy([SORT_DESC])->one();
            
        }
        return $this->render('createprint', [
            'modelCashSale' => $modelCashSale,
            'modelCashSaleSub'=>$modelCashSaleSub,
            'modelSales'=>$modelSales
        ]);
    }



     public function actionPreview($id)
    {
   
        $model = $this->findModel($id);
        $modelCashSaleSub = CashSaleSub::find()->where(['cash_sale_id'=>$model->id])->all();

         
        return $this->render('preview', [
             'model' =>$model,
             'modelCashSaleSub'=>$modelCashSaleSub,
        ]);
    }



    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }



    /**
     * Finds the CashSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CashSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashSale::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}










