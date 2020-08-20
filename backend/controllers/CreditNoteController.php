<?php

namespace backend\controllers;

use Yii;
use app\models\CreditNote;
use app\models\CreditNoteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\models\JobCard;
use app\models\Customers;
use app\models\CreditNoteSub;
use app\models\JobCardSub;
use yii\helpers\Html;  
use yii\helpers\ArrayHelper;
use app\models\Model;
use app\models\PrefomaInvoice;
use app\models\PrefomaInvoiceSub;
use app\models\CashSale;
use app\models\CashSaleSub;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use app\models\Invoice;

/**
 * CreditNoteController implements the CRUD actions for CreditNote model.
 */
class CreditNoteController extends Controller
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
     public function accessRules()

        {

        array('deny',  // deny anonymous users

        'users'=>array('?'),);

        }


    /**
     * Lists all CreditNote models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CreditNoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CreditNote model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    /**
     * Creates a new CreditNote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
       $model = new CreditNote();
        $modelsSubcategory = [new CreditNoteSub];
        
        if ($model->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(CreditNoteSub::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
           
            $modelCreditnote = CreditNote::find()->all();
            $number_array = count($modelCreditnote);
            $credit_note_num = $number_array + 1;
            $deposit = 0;
            $job_card_number = $model->job_card_number; 

           $costs = floatval(preg_replace('/[^\d.]/', '', $model->amount));
          
            date_default_timezone_set("Africa/Nairobi"); 
           

           //$model->credit_note_num = sprintf('CR'.'%04d', $credit_note_num);
            

            $model->created_at = date('Y:m:d H:i:s');
            $model->created_by = \Yii::$app->user->identity->id;
            $model->status = 11;


            foreach ($modelsSubcategory as  $value) {
                 //$job_card_id = $value->job_card_number;
                 $description = $value->description;
                 $modelcostqty = $value->cost_qty;
                  $modelquantity = $value->quantity;
                

                $modelJob = JobCard::find()->where(['id'=>$job_card_number])->all();
                 foreach ($modelJob as $val) {
                 $cost = $val['cost'];
                  $payment = floatval(preg_replace('/[^\d.]/', '', $val['payment']));
                   $customer_id = $val['customer_id'];
                 $job_card_num = $val['job_card_number'];
                 $tax = $val['tax_amount'];
                 $balance = $val['balance'];
                 // $sales_tax = $val['sales_tax'];
                  $total_amount = floatval(preg_replace('/[^\d.]/', '', $val['total_amount']));

                 // $sale_tax = $val['sales_tax'];
                 // $total_amount = $val['total_amount'];
               
                    $modelJobCardSub = JobCardSub::find()->where(['id'=>$description])->all();
                    foreach ($modelJobCardSub as  $values) {
                    $costqty = $values['cost_qty'];
                    $quantity = $values['quantity'];
                    $idsub = $values['id'];
                }
                    
                   $newcostqty = $costqty - $modelcostqty;
                   $newquantity = $quantity -  $modelquantity;
                   $newmodeltotal = $newquantity * $newcostqty;
                                
                  $cost_amount = floatval(preg_replace('/[^\d.]/', '', $cost));
                  $newcost = $cost_amount - $costs;
                  $newsale_tax = ($newcost * $tax) / 100;
                  $newtototal = $newsale_tax + $newcost;
                  $newbalance = $newtototal - $payment;

                  $model->amount = number_format($costs);
                 


                  if($newbalance < $payment){

                    $newpayment = $payment + $newbalance;
                     if($newbalance < 0){
                    $newbalance = 0;
                  }
                     $newamount = $payment - $newpayment ;

                  }else if($newbalance == $payment){

                  }else {

                  }
            

                   $modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_num])->all();
                   $modelInvoice  = Invoice::find()->where(['job_card_number'=>$job_card_number])->all();
                   if(!empty($modelInvoice)){
                    foreach ($modelInvoice as $value) {
                        $invoice_id = $value['id'];
                    }
                     if($newbalance < $payment){
                    Yii::$app->db->createCommand()
                  ->update('table_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'payment'=>$newpayment,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute(); 


                   Yii::$app->db->createCommand()
                 ->update('table_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();
             }

             else if($newbalance == $payment){
                Yii::$app->db->createCommand()
                  ->update('table_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute();   

                   Yii::$app->db->createCommand()
                 ->update('table_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();
             }
             else{

                  Yii::$app->db->createCommand()
                  ->update('table_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute();   

                   Yii::$app->db->createCommand()
                 ->update('table_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();

             }
         }
         
                 
                 if(!empty($modelCashSale)){
                  foreach ($modelCashSale as  $value) {
                    $cash_id = $value['id'];
                    //$cost_cash = $value['cost'];
                  }
                if($newbalance < $payment){
                    Yii::$app->db->createCommand()
                  ->update('table_cash_sale', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'payment'=>$newpayment,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute(); 

                    Yii::$app->db->createCommand()
                  ->insert('table_refund', ['job_card_number' => $job_card_num, 'customer_id'=>$customer_id,   'amount'=>$newamount, 'created_at'=>date('Y:m:d H:i:s'), 'created_by'=>\Yii::$app->user->identity->id, 'status'=>11])
                 ->execute(); 



                   Yii::$app->db->createCommand()
                 ->update('table_cash_sale_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();
             }

             else if($newbalance == $payment){
                Yii::$app->db->createCommand()
                  ->update('table_cash_sale', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute();   

                   Yii::$app->db->createCommand()
                 ->update('table_cash_sale_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();
             }
             else{

                  Yii::$app->db->createCommand()
                  ->update('table_cash_sale', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
                 ->execute();   

                   Yii::$app->db->createCommand()
                 ->update('table_cash_sale_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['cash_sale_id'=> $cash_id])
                 ->execute();

             }
         }
         
                

                //prefoma credit note
                    $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$job_card_num])->all();
                    if(!empty($modelPrefomaInvoice)){
                   
                    foreach ($modelPrefomaInvoice as  $value) {
                        $prefoma_id = $value['id'];
                    }

                 if($newbalance < $payment){
                Yii::$app->db->createCommand()
               ->update('table_prefoma_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance,'payment'=>$newpayment,'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
               ->execute();   

                Yii::$app->db->createCommand()
                  ->insert('table_refund', ['job_card_number' => $job_card_num, 'customer_id'=>$customer_id,  'amount'=>$newamount, 'created_at'=>date('Y:m:d H:i:s'), 'created_by'=>\Yii::$app->user->identity->id, 'status'=>11])
                 ->execute(); 

                   Yii::$app->db->createCommand()
                  ->update('table_prefoma_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['prefoma_number'=> $prefoma_id])
                  ->execute();

                 }
                     else if($newbalance == $payment){
                Yii::$app->db->createCommand()
               ->update('table_prefoma_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
               ->execute();   


                   Yii::$app->db->createCommand()
                  ->update('table_prefoma_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['prefoma_number'=> $prefoma_id])
                  ->execute();

                }else {
                     Yii::$app->db->createCommand()
               ->update('table_prefoma_invoice', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['job_card_number'=> $job_card_num])
               ->execute();   


                   Yii::$app->db->createCommand()
                  ->update('table_prefoma_invoice_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['prefoma_number'=> $prefoma_id])
                  ->execute();

                }
            
            }

            if($newbalance  < $payment){
                 Yii::$app->db->createCommand()
              ->update('table_job_card', ['cost' => $newcost,'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance, 'payment'=>$newpayment, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $job_card_number])
              ->execute();

              Yii::$app->db->createCommand()
             ->update('table_job_card_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity, 'totals'=>$newmodeltotal], ['id'=> $idsub])
             ->execute();

           }
            else if($newbalance == $payment){
             Yii::$app->db->createCommand()
              ->update('table_job_card', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $job_card_number])
              ->execute();

              Yii::$app->db->createCommand()
             ->update('table_job_card_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity, 'totals'=>$newmodeltotal], ['id'=> $idsub])
             ->execute();

             }
             else {

                  Yii::$app->db->createCommand()
              ->update('table_job_card', ['cost' => $newcost, 'total_amount'=>$newtototal, 'sales_tax'=>$newsale_tax, 'balance'=>$newbalance, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $job_card_number])
              ->execute();

              Yii::$app->db->createCommand()
             ->update('table_job_card_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity, 'totals'=>$newmodeltotal], ['id'=> $idsub])
             ->execute();


             }
           }    
           
       }


                  
            $model->customer_id = $customer_id;

             $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if($flag = $model->save(false)){
                      
                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                           
                               $modelsSubcategorys->credit_note_id = $model->credit_note_id;
                            if(! ($flag = $modelsSubcategorys->save(false))){
                                $transaction->rollBack();
                                break;
                            }

                        }
 
                    }
                    if($flag){
                        $transaction->commit();
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Credit Note added successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                }
                catch (Exception $e){
                    $transaction->rollBack();
                }
            
        }
        return $this->renderAjax('create', [
            'model' => $model,
            'modelsSubcategory'=>(empty($modelsSubcategory))? [new CreditNoteSub] : $modelsSubcategory
        ]);
    }





    /**
     * Updates an existing CreditNote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsSubcategory = $model->subCategorycredit;

        if ($model->load(Yii::$app->request->post())) {
         $oldIDs = ArrayHelper::map($modelsSubcategory, 'id', 'id');
            $modelsSubcategory = Model::createMultiple(CreditNoteSub::classname(), $modelsSubcategory);
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSubcategory, 'id', 'id')));

              foreach ($modelsSubcategory as  $value) {
              $id = $value->job_card_number;
              $modelcostqty = $value->cost_qty;
              $modelquantity = $value->quantity;
                 
              $modelJob = JobCard::find()->where(['id'=>$id])->all();
              foreach ($modelJob as $val) {
               $cost = $val['cost'];
               $deposit_paid = $val['deposit_paid'];
               $customer_id = $value['customer_id'];
               $job_card_number = $val['job_card_number'];
                   

                          
               $modelJobCardSub = JobCardSub::find()->where(['job_card_id'=>$id])->all();
               foreach ($modelJobCardSub as  $values) {
               $costqty = $values['cost_qty'];
               $quantity = $values['quantity'];
                        
               $newcostqty = $costqty - $modelcostqty;
               $newquantity = $quantity -  $modelquantity;
                                
                       
                $newcost = $cost - $costs;
                $newbalance = $newcost - $deposit_paid;

                

                        Yii::$app->db->createCommand()

             ->update('table_job_card', ['cost' => $newcost, 'balance'=>$newbalance, 'updated_at'=>date('Y:m:d H:i:s'), 'updated_by'=>\Yii::$app->user->identity->id], ['id'=> $id])

             ->execute();

              Yii::$app->db->createCommand()

             ->update('table_job_card_sub', ['cost_qty' => $newcostqty, 'quantity'=>$newquantity], ['job_card_id'=> $id])

             ->execute();
                         
               }
           }

}                  
            $model->customer_id = $customer_id;



                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            CreditNoteSub::deleteAll(['id' => $deletedIDs]);
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


        return $this->renderAjax('update', [
            'model' => $model,
             'modelsSubcategory' => (empty($modelsSubcategory)) ? [new CreditNoteSub] : $modelsSubcategory
        ]);
    }




    public function actionCustomerinvoice(){
      $out = [];
      $space = " - ";
     // global $package_cost;
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
           if ($parents != null) {
                $customer_id = $parents[0];
                $model = JobCard::find()
                         ->where(['customer_id'=>$customer_id])
                         ->all();
                foreach ($model as $key => $value) {
                    //$package_cost =$value['package_cost'];
                    # code...
                  

                    $out[] = ['id' => $value['id'], 'name' =>$value['job_card_number'] ];

                        if ($key == 0) {
                        $selected = $value['id'];
                    }
               
                }
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;

            }
        

            }
        
        echo Json::encode(['output' => '', 'selected'=>'']);
    }



     public function actionDescription(){
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
           if ($parents != null) {
                $id = $parents[0];
                $model = JobCardSub::find()
                         ->where(['job_card_id'=>$id])
                         ->all();
                foreach ($model as $key => $value) {
                    //$package_cost =$value['package_cost'];
                    # code...
                  

                    $out[] = ['id' => $value['id'], 'name' =>$value['description'] ];

                        if ($key == 0) {
                        $selected = $value['id'];
                    }
               
                }
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;

            }
        

            }
        
        echo Json::encode(['output' => '', 'selected'=>'']);
    }




    //print out

    public function actionPrint() {

        //return $this->render('print');
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelCreditnote = CreditNote::find()->where(['credit_note_id'=>$id])->all();
            $modelCredinoteSub= CreditNoteSub::find()->where(['credit_note_id'=>$id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelCreditnote' => $modelCreditnote,
            'modelCredinoteSub'=>$modelCredinoteSub]),
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
            'modelCreditnote' => $modelCreditnote,
            'modelCredinoteSub'=>$modelCredinoteSub,
        ]);
}
      
}
   


    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelCreditnote = CreditNote::find()->where(['credit_note_id'=>$id])->all();
            $modelCredinoteSub= CreditNoteSub::find()->where(['credit_note_id'=>$id])->all();
            
        }
        return $this->render('createprint', [
            'modelCreditnote' => $modelCreditnote,
            'modelCredinoteSub'=>$modelCredinoteSub,
        ]);
    }





    /**
     * Deletes an existing CreditNote model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CreditNote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CreditNote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CreditNote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
