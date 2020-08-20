<?php

namespace backend\controllers;

use Yii;
use app\models\JobCard;
use app\models\JobCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Customers;
use app\models\JobCardSub;
use app\models\Model;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\CashSale;
use app\models\CashSaleSub;
use app\models\PrefomaInvoice;
use app\models\PrefomaInvoiceSub;
use app\models\Invoice;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * JobCardController implements the CRUD actions for JobCard model.
 */
class JobCardController extends Controller
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
     * Lists all JobCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JobCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         
         if (Yii::$app->request->post('hasEditable')) {
          $id=$_POST['editableKey'];
          $model = JobCard::findOne($id);
          $out = Json::encode(['output'=>'', 'message'=>'']);
          $post = [];
          $redirectFlag = false;
          $posted = current($_POST['JobCard']);
          $post['JobCard'] = $posted;
          if ($model->load($post)) {
            date_default_timezone_set("Africa/Nairobi");
            $model->updated_by  = \Yii::$app->user->identity->id;
            $model->updated_at = date('Y:m:d H:i:s');
           $model->save(false);
                $output ='me';
                 $out = Json::encode(['output'=>'', 'message'=>'']);
            // \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
                   Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Changed successfully.']);
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
     * Displays a single JobCard model.
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
     * Creates a new JobCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */




    public function actionCreate()
    {
        $model = new JobCard();
        $modelsSubcategory = [new JobCardSub];
        $modelCashSale = new CashSale();
        $modelCashSaleSub = [new CashSaleSub];
        $modelPrefomaInvoice = new PrefomaInvoice();
        $modelPrefomaInvoiceSub= [new PrefomaInvoiceSub];


        
        if ($model->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(JobCardSub::classname());
            //$modelCashSaleSub = Model::createMultiple(CashSaleSub::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());

            // if(isset($_POST['cost'])){
            //     $cost = $_POST['cost'];
            // }    

            $models = JobCard::find()->all();
            $modelJob = JobCard::find()->all();
            $number_array = count($modelJob);
            // $job_card_num = $number_array + 1;
            //$deposit = 0;

            $modelCustomer = Customers::find()->where(['customer_id'=>$model->customer_id])->all();
            foreach ($modelCustomer as $value) {
                // $customer_name = $value['customer_names'];
                $phone_num = $value['customer_phone'];
            
        }   

        

           $costs = floatval(preg_replace('/[^\d.]/', '', $model->cost));
           $payment = floatval(preg_replace('/[^\d.]/', '', $model->payment));
           $balance = floatval(preg_replace('/[^\d.]/', '', $model->balance));
           // $balance = $costs - $deposit;
           date_default_timezone_set("Africa/Nairobi"); 


            if($model->transaction_type == 'cash_sale'){
              $status = $model->status = 13;  
               // $model->job_card_number = sprintf('CH'.'%04d', $job_card_num);
            }else if($model->transaction_type == 'profoma_invoice'){
                $status = $model->status = 11;
                // $model->job_card_number = sprintf('INV'.'%04d', $job_card_num);
            }
           // $model->job_card_number = $job_card_number;
          // $model->customer_name = $customer_name;
            


            $model->cost = number_format($costs);
            $model->payment = number_format($payment);

            $model->balance = number_format($balance);
            $model->created_at = date('Y:m:d H:i:s');
            $model->created_by = \Yii::$app->user->identity->id;
            $model->office = \Yii::$app->user->identity->office_location;
            $model->status = 11;

             $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if($flag = $model->save(false)){

                    if($status == 13){
                    $modelCashSale->job_card_number  = $model->job_card_number;
                    $modelCashSale->customer_id = $model->customer_id;
                    $modelCashSale->cost = number_format($costs); 
                    $modelCashSale->payment = number_format($payment); 
                    $modelCashSale->tax_amount = $model->tax_amount; 
                    $modelCashSale->sales_tax = $model->sales_tax;
                    $modelCashSale->total_amount = $model->total_amount; 
                    $modelCashSale->balance = number_format($balance);
                    $modelCashSale->created_at = date('Y:m:d H:i:s');
                    $modelCashSale->created_by = \Yii::$app->user->identity->id;
                    $modelCashSale->status = $model->status;
                    $modelCashSale->office = \Yii::$app->user->identity->office_location;
                    $modelCashSale->save(false);
                     }else if($status == 11){
                    $modelPrefomaInvoice->job_card_number  = $model->job_card_number;
                    $modelPrefomaInvoice->customer_id = $model->customer_id;
                    $modelPrefomaInvoice->cost = number_format($costs); 
                    $modelPrefomaInvoice->payment = number_format($payment); 
                    $modelPrefomaInvoice->tax_amount = $model->tax_amount; 
                    $modelPrefomaInvoice->sales_tax = $model->sales_tax;
                    $modelPrefomaInvoice->total_amount = $model->total_amount; 
                    $modelPrefomaInvoice->balance = number_format($balance);
                    $modelPrefomaInvoice->created_at = date('Y:m:d H:i:s');
                    $modelPrefomaInvoice->created_by = \Yii::$app->user->identity->id;
                    $modelPrefomaInvoice->status = $model->status;
                    $modelPrefomaInvoice->office = \Yii::$app->user->identity->office_location;
                    $modelPrefomaInvoice->approval = 11;
                    $modelPrefomaInvoice->save(false);
                     }
                        $data = [];
                        $modelData = array();
                        $modelCashSaleSubes = array();
                        

                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            //if(empty($modelsSubcategorys->subcategory)){
                                $modelsSubcategorys->job_card_id = $model->id;
                                if($modelsSubcategorys->discount == ''){
                                    $modelsSubcategorys->discount = 0;
                                }
                                $modelsSubcategorys->save(false);
                                  $data [] = [$modelCashSale->id, $modelsSubcategorys->id, $modelsSubcategorys->quantity, $modelsSubcategorys->service_type, $modelsSubcategorys->description, $modelsSubcategorys->cost_qty, $modelsSubcategorys->discount];

                                   $datas [] = [$modelPrefomaInvoice->id, $modelsSubcategorys->id, $modelsSubcategorys->quantity, $modelsSubcategorys->service_type, $modelsSubcategorys->description, $modelsSubcategorys->cost_qty, $modelsSubcategorys->discount];

                            
                            if(! ($flag = $modelsSubcategorys->save(false))){   
                                $transaction->rollBack();
                                break;
                            }
                        }
                         if($status == 13){
                             Yii::$app->db->createCommand()->batchInsert('table_cash_sale_sub', ['cash_sale_id','job_card_id','quantity','service_type','description', 'cost_qty', 'discount'] ,$data)->execute(); 
                         }else if($status == 11){
                                Yii::$app->db->createCommand()->batchInsert('table_prefoma_invoice_sub', ['prefoma_number','job_card_id','quantity','service_type','description', 'cost_qty', 'discount'] ,$datas)->execute(); 
                         }


                    }
                    if($flag){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Menu created successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                }
                catch (Exception $e){
                    $transaction->rollBack();
                }
            
        }
        return $this->render('create', [
            'model' => $model,
            'modelsSubcategory'=>(empty($modelsSubcategory))? [new JobCardSub] : $modelsSubcategory
        ]);
    }

 
    /**
     * Updates an existing JobCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsSubcategory = $model->subCategoryjob;
        //$modelUpdate = $modelsSubcategory->subCategoryCashsalesub;
        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsSubcategory, 'id', 'id');
            $modelsSubcategory = Model::createMultiple(JobCardSub::classname(), $modelsSubcategory);
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSubcategory, 'id', 'id')));

            $costs = floatval(preg_replace('/[^\d.]/', '', $model->cost));
            $payment = floatval(preg_replace('/[^\d.]/', '', $model->payment));
            $balance = floatval(preg_replace('/[^\d.]/', '', $model->balance));

             $modelCashSale = CashSale::find()->where(['job_card_number'=>$model->job_card_number])->all();
             $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$model->job_card_number])->all();

             if(!empty($modelCashSale)){
             foreach ($modelCashSale as $value) {
                 $cash_sale_id = $value['id'];
                 $cash_status = $value['status'];
            }
        
                foreach ($modelCashSale as  $value) {
                    $ids = $value['id'];
                Yii::$app->db->createCommand()

                 ->update('table_cash_sale', ['customer_id'=>$model->customer_id, 'cost'=>number_format($costs),'payment'=>number_format($payment),'tax_amount'=>$model->tax_amount,'sales_tax'=>$model->sales_tax,'total_amount'=>$model->total_amount,'balance'=>number_format($balance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $ids])

                 ->execute(false);
                }
         
     }

          if(!empty($modelPrefomaInvoice)){
              foreach ($modelPrefomaInvoice as $value) {
                 $prefoma_id = $value['id'];
                 $prefoma_status = $value['status'];
                 //$prefoma_number = $value['id'];
            }
             Yii::$app->db->createCommand()

                 ->update('table_prefoma_invoice', ['customer_id'=>$model->customer_id, 'cost'=>number_format($costs),'payment'=>number_format($payment),'tax_amount'=>$model->tax_amount,'sales_tax'=>$model->sales_tax,'total_amount'=>$model->total_amount,'balance'=>number_format($balance),'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $prefoma_id])

                 ->execute(false);

         }
       


                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            JobCardSub::deleteAll(['id' => $deletedIDs]);
                        }

                        
                        $data =array();
                        $datas = array();
                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            $modelsSubcategorys->job_card_id = $model->id;
                             $modelsSubcategorys->save(false);
                             $modelCashSaleupdate = CashSale::find()->where(['job_card_number'=>$model->job_card_number])->all();
                             foreach ($modelCashSaleupdate as  $value) {
                                 $cashid = $value['id'];

                             } $modelProfomaInvoiceupdate = PrefomaInvoice::find()->where(['job_card_number'=>$model->job_card_number])->all();
                             foreach ($modelProfomaInvoiceupdate as  $value) {
                                 $prefomaid = $value['id'];

                             }
                                if(!empty($modelCashSale)){ 
                                 //get array for attributes to insert to either cash sale sub or prefoma sub   
                                $data [] = [$cashid, $modelsSubcategorys->id, $modelsSubcategorys->quantity, $modelsSubcategorys->service_type, $modelsSubcategorys->description, $modelsSubcategorys->cost_qty, $modelsSubcategorys->discount];
                                }
                              
                               if(!empty($modelPrefomaInvoice)){
                                  $datas [] = [$prefomaid, $modelsSubcategorys->id, $modelsSubcategorys->quantity, $modelsSubcategorys->service_type, $modelsSubcategorys->description, $modelsSubcategorys->cost_qty, $modelsSubcategorys->discount];
                              }
                
                     
                             
                            if (! ($flag = $modelsSubcategorys->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        
                    
                     if(!empty($modelCashSale)){
                    //insert particulars to the cash table sub
                        CashSaleSub::deleteAll(['=','cash_sale_id', $cash_sale_id]);
                       Yii::$app->db->createCommand()->batchInsert('table_cash_sale_sub', ['cash_sale_id','job_card_id','quantity','service_type','description', 'cost_qty', 'discount'] ,$data)->execute();
                    
                } if(!empty($modelPrefomaInvoice)){
                       //insert particulars to the prefoma table sub
                        PrefomaInvoiceSub::deleteAll(['=','prefoma_number', $prefoma_id]);
                       Yii::$app->db->createCommand()->batchInsert('table_prefoma_invoice_sub', ['prefoma_number','job_card_id','quantity','service_type','description', 'cost_qty', 'discount'] ,$datas)->execute();
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
             'modelsSubcategory' => (empty($modelsSubcategory)) ? [new JobCardSub] : $modelsSubcategory
        ]);
    }


    public function actionApprove(){
        if(isset($_POST['approve'])){
            $status = $_POST['approve'];
            $job_card_number = $_POST['job_card_number'];
              Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => $status],  ['job_card_number'=> $job_card_number])

             ->execute();
             return $this->redirect('index');
        }

    }


      public function actionCancel(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            foreach ($modelJobCard as $value) {
                $job_card_number = $value['job_card_number'];
            }
            $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$job_card_number])->all();

             Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 9],  ['id'=> $id])

             ->execute();

             if(!empty($modelCashSale)){
             Yii::$app->db->createCommand()

             ->update('table_cash_sale', ['status' => 9],  ['job_card_number'=> $job_card_number])

             ->execute();
         }
            if(!empty($modelPrefomaInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['status' => 9, 'approval'=>9],  ['job_card_number'=> $job_card_number])

             ->execute();
            }

             return $this->redirect('/job-card/index');
        }
    }

public function actionComplete(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            foreach ($modelJobCard as $value) {
                $job_card_number = $value['job_card_number'];
            }
            $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$job_card_number])->all();
             $modelInvoice = Invoice::find()->where(['job_card_number'=>$job_card_number])->all();

             Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 12],  ['id'=> $id])

             ->execute();

             if(!empty($modelCashSale)){
             Yii::$app->db->createCommand()

             ->update('table_cash_sale', ['status' => 12],  ['job_card_number'=> $job_card_number])

             ->execute();
         }
            if(!empty($modelPrefomaInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['status' => 12],  ['job_card_number'=> $job_card_number])

             ->execute();
            }
             if(!empty($modelInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_invoice', ['status' => 12],  ['job_card_number'=> $job_card_number])

             ->execute();
            }

             return $this->redirect('/job-card/index');
        }
    }

    public function actionPicked(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            foreach ($modelJobCard as $value) {
                $job_card_number = $value['job_card_number'];
            }
            $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$job_card_number])->all();
            $modelInvoice = Invoice::find()->where(['job_card_number'=>$job_card_number])->all();

             Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 13],  ['id'=> $id])

             ->execute();

             if(!empty($modelCashSale)){
             Yii::$app->db->createCommand()

             ->update('table_cash_sale', ['status' => 13],  ['job_card_number'=> $job_card_number])

             ->execute();
         }
            if(!empty($modelPrefomaInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['status' => 13],  ['job_card_number'=> $job_card_number])

             ->execute();
            }
             if(!empty($modelInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_invoice', ['status' => 13],  ['job_card_number'=> $job_card_number])

             ->execute();
            }

             return $this->redirect('/job-card/index');
        }
    }


    /**
     * Deletes an existing JobCard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPrint() {

        //return $this->render('print');
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            $modelJobCardSub = JobCardSub::find()->where(['job_card_id'=>$id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelJobCard' => $modelJobCard,
            'modelJobCardSub'=>$modelJobCardSub]),
       // 'content' => $this->renderPartial('createprint'),
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            'SetTitle' => 'Receipt',
            //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
            //'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
            //'SetFooter' => ['|Page {PAGENO}|'],
            //'SetAuthor' => 'Kartik Visweswaran',
            //'SetCreator' => 'Kartik Visweswaran',
            //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
        ]
    ]);
    return $pdf->render('createprint', [
            'modelJobCard' => $modelJobCard,
            'modelJobCardSub'=>$modelJobCardSub,
           
        ]);
}
      
}
   


    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            $modelJobCardSub = JobCardSub::find()->where(['job_card_id'=>$id])->all();
            
        }
        return $this->render('createprint', [
            'modelJobCard' => $modelJobCard,
            'modelJobCardSub'=>$modelJobCardSub,
            //'modelCustomer' =>$modelCustomer
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the JobCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return JobCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobCard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
