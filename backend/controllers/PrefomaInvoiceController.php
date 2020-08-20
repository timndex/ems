<?php

namespace backend\controllers;

use Yii;
use app\models\PrefomaInvoice;
use app\models\PrefomaInvoiceSub;
use app\models\PrefomaInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Customers;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\models\Model;
use yii\helpers\Html;
use Swift_Attachment;
use app\models\Invoice;
use app\models\InvoiceSub;
use app\models\PrefomaInvoiceDetails;
use app\models\PrefomaInvoiceTotal;
use yii\filters\AccessControl;
use app\models\Mail;
use app\models\MailingList;
/**
 * PrefomaInvoiceController implements the CRUD actions for PrefomaInvoice model.
 */
class PrefomaInvoiceController extends Controller
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
     * Lists all PrefomaInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrefomaInvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['office'=> \Yii::$app->user->identity->office_location])->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrefomaInvoice model.
     * @param integer $id
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
     * Creates a new PrefomaInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PrefomaInvoice();
        $modelsSubcategory = [new PrefomaInvoiceSub];
      
        
        if ($model->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(PrefomaInvoice::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());

            // if(isset($_POST['cost'])){
            //     $cost = $_POST['cost'];
            // }    

            $models = PrefomaInvoice::find()->all();
            $modelJob = PrefomaInvoice::find()->all();
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
                            // if(empty($modelsSubcategorys->subcategory)){
                            $modelsSubcategorys->job_card_id = $model->id;
                            if(! ($flag = $modelsSubcategorys->save(false))){
                                $transaction->rollBack();
                                break;
                            }
                       // }
                            # code...
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
            'modelsSubcategory'=>(empty($modelsSubcategory))? [new PrefomaInvoiceSub] : $modelsSubcategory
            
        ]);
    }

    /**
     * Updates an existing PrefomaInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $modelPrefoma  = $this->findModel($id);
        $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$modelPrefoma->id])->all();
        $modelsSubcategory = $modelPrefoma->subCategoryprefomadetails;
        $modelsSubcategoryamount = $modelPrefoma->subCategoryprefomamount;

        if ($modelsSubcategoryamount->load(Yii::$app->request->post())) {
            $oldIDs = ArrayHelper::map($modelsSubcategory, 'id', 'id');
            $modelsSubcategory = Model::createMultiple(PrefomaInvoiceDetails::classname(), $modelsSubcategory);
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSubcategory, 'id', 'id')));

              $flag = '';
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                        if (! empty($deletedIDs)) {
                            PrefomaInvoiceDetails::deleteAll(['id' => $deletedIDs]);
                        }
                          
                          $modelsSubcategoryamount->save(false);
                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                              $modelsSubcategorys->prefoma_number =  $modelPrefoma->id;
                             $modelsSubcategorys->save(false);

                            if (! ($flag = $modelsSubcategorys->save(false))) {
                                $transaction->rollBack();
                                break;
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
             'modelsSubcategory' => (empty($modelsSubcategory)) ? [new PrefomaInvoiceDetails] : $modelsSubcategory,
             'modelPrefoma' =>$modelPrefoma,
             'modelPrefomaSub'=>$modelPrefomaSub,
             'modelsSubcategoryamount'=>$modelsSubcategoryamount
        ]);
    }



  public function actionCreatequotation($id)
    {
   
        $modelPrefoma = $this->findModel($id);
        $modelsSubcategory = [new PrefomaInvoiceDetails];
        $modelsSubcategoryamount = new PrefomaInvoiceTotal;
        $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$modelPrefoma->id])->all();

         if ($modelsSubcategoryamount->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(PrefomaInvoiceDetails::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
             //Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
                
                 $flag = '';
                $transaction = \Yii::$app->db->beginTransaction();

                try{    
                        $modelsSubcategoryamount->prefoma_number= $modelPrefoma->id;
                        $modelsSubcategoryamount->save(false);
                      
                          foreach ($modelsSubcategory as $modelsSubcategorys) {
                              $modelsSubcategorys->prefoma_number =  $modelPrefoma->id;
                             $modelsSubcategorys->save(false);

                            if (! ($flag = $modelsSubcategorys->save(false))) {
                                $transaction->rollBack();
                                break;
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
            
   
        return $this->render('createquotation', [
            // 'model' => $model,
             'modelsSubcategory' => (empty($modelsSubcategory)) ? [new PrefomaInvoiceDetails] : $modelsSubcategory,
             'modelPrefoma' =>$modelPrefoma,
             'modelPrefomaSub'=>$modelPrefomaSub,
             'modelsSubcategoryamount'=>$modelsSubcategoryamount

        ]);
    }




    public function actionMail($id){

    $model = $this->findModel($id);
    $modelMail = new Mail();
   if ($modelMail->load(Yii::$app->request->post())) { 
            $modelPrefoma = PrefomaInvoice::find()->where(['id'=>$id])->all();
            foreach ($modelPrefoma as $value) {
            $customer_id = $value['customer_id'];
            }
            $modelCustomer = Customers::find()->where(['customer_id'=>$customer_id])->all();
            foreach ($modelCustomer as  $value) {
                $customer_name = $value['customer_names'];
            }
            $modelMailingList = MailingList::find()->where(['customer_id'=>$customer_id])->all();
            //$mails = array();
           
            $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$id])->all();
             $modelPrefomaInvoiceDetails =PrefomaInvoiceDetails::find()->where(['prefoma_number'=>$id])->all();
             $modelsPrefomaDetailsTotal = PrefomaInvoiceTotal::find()->where(['prefoma_number'=>$id])->all();


     Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
         'filename' => $customer_name.'.pdf',
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('_printout', ['modelPrefoma' => $modelPrefoma,
             'modelPrefomaSub'=>$modelPrefomaSub, 'modelPrefomaInvoiceDetails'=>$modelPrefomaInvoiceDetails, 'modelsPrefomaDetailsTotal'=>$modelsPrefomaDetailsTotal]),
       // 'content' => $this->renderPartial('createprint'),
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            'SetTitle' => 'Receipt',
            'SetFooter' => ['|Page {PAGENO}|'],
        ]
    ]);
        // $emails = "'" . implode("', '", $mails) . "'";
        // $mailing = str_replace(' ', '', $emails);
        //$mailall = array($mailing);
        $modelMail->save(false);
        foreach ($modelMailingList as  $value) {
                 $mails =array($value['email']);


        $content = $pdf->content;
        $filename = $pdf->filename;
        $mpdf = new \Mpdf\Mpdf();
        $name = 'Cesscolina Sales';
         
            
         $stylesheet = file_get_contents(Yii::getAlias('@backend')."/web/assets/proui/css/printcss.css");
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($content); //pdf is a name of view file responsible for this pdf document
    
        $mpdf->Output(Yii::getAlias("@backend")."/web/uploads/pdf/".$filename,"F");
        Yii::$app->mailer->compose()->setFrom(array('sales@cesscolina.co.ke'=> $name))
        ->setTo($mails) //send array of mails ([''])
        ->setSubject($modelMail->subject)
        ->setTextBody($modelMail->message)
        ->attach(Yii::getAlias("@backend")."/web/uploads/pdf/".$filename)
        ->send();
        }
        if($modelMail->save(false)){
        echo 'success';
        
        }
        return $this->redirect('index');
        }
     return $this->renderAjax('createmail', [
             'model' => $model,
             'modelMail'=>$modelMail,
        ]);


}


//contains function for the format of print out on index tab
public function actionPrint() {

        //return $this->render('print');
    if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelPrefoma = PrefomaInvoice::find()->where(['id'=>$id])->all();
            $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$id])->all();
            $modelPrefomaInvoiceDetails =PrefomaInvoiceDetails::find()->where(['prefoma_number'=>$id])->all();
            $modelsPrefomaDetailsTotal = PrefomaInvoiceTotal::find()->where(['prefoma_number'=>$id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelPrefoma' => $modelPrefoma,
             'modelPrefomaSub'=>$modelPrefomaSub, 'modelPrefomaInvoiceDetails'=>$modelPrefomaInvoiceDetails,  'modelsPrefomaDetailsTotal'=>$modelsPrefomaDetailsTotal,]),
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
            'modelPrefoma' => $modelPrefoma,
            'modelPrefomaSub'=>$modelPrefomaSub,
             'modelPrefomaInvoiceDetails'=>$modelPrefomaInvoiceDetails,
             'modelsPrefomaDetailsTotal'=>$modelsPrefomaDetailsTotal
           
        ]);
}
      
}


    
//create printout 
    public function actionCreateprint(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
             $modelPrefoma = PrefomaInvoice::find()->where(['id'=>$id])->all();
            $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$id])->all();
            $modelPrefomaInvoiceDetails =PrefomaInvoiceDetails::find()->where(['prefoma_number'=>$id])->all();
            $modelsPrefomaDetailsTotal = PrefomaInvoiceTotal::find()->where(['prefoma_number'=>$id])->all();
            
        }
        return $this->render('createprint', [
            'modelPrefoma' => $modelPrefoma,
            'modelPrefomaSub'=>$modelPrefomaSub,
            'modelPrefomaInvoiceDetails'=>$modelPrefomaInvoiceDetails,
            'modelsPrefomaDetailsTotal'=>$modelsPrefomaDetailsTotal
            
        ]);
    }



    public function actionCancel(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];

             Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['status' => 9, 'approval'=>9],  ['id'=> $id])

             ->execute();
        

               Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 9],  ['id'=> $id])

             ->execute();

              return $this->redirect('/prefoma-invoice/index');

           
        }
    }



    public function actionComplete(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelJobCard = JobCard::find()->where(['id'=>$id])->all();
            foreach ($modelJobCard as $value) {
                $job_card_number = $value['job_card_number'];
            }
            //$modelCashSale = CashSale::find()->where(['job_card_number'=>$job_card_number])->all();
            $modelPrefomaInvoice = PrefomaInvoice::find()->where(['job_card_number'=>$job_card_number])->all();

             Yii::$app->db->createCommand()

             ->update('table_job_card', ['status' => 12],  ['id'=> $id])

             ->execute();


            if(!empty($modelPrefomaInvoice)){
             Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['status' => 12],  ['job_card_number'=> $job_card_number])

             ->execute();
            }

             return $this->redirect('/job-card/index');
        }
    }
    



//contains function for the format of print out on index tab
    public function prindutdoc($id){
          if(isset($_GET['id'])){
            $id = $_GET['id'];
            $modelPrefoma = PrefomaInvoice::find()->where(['id'=>$id])->all();
            $modelPrefomaSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$id])->all();
           

    Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => \Yii::getAlias('@webroot') .'/assets/proui/css/printcss.css',
        'content' =>  $this->renderPartial('createprint', ['modelPrefoma' => $modelPrefoma,
             'modelPrefomaSub'=>$modelPrefomaSub]),
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
            'modelPrefoma' => $modelPrefoma,
            'modelPrefomaSub'=>$modelPrefomaSub,
           
        ]);
     }
        
    }
   


//approve prefoma invoice 
    //14
       public function actionApprove(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            //$job_card_number = $_POST['job_card_number'];
            $model = PrefomaInvoice::find()->where(['id'=>$id])->all();
            foreach ($model as $value) {
                // $prefoma_number =$value['id'];
                $payment = $value['payment'];
                $job_card_number =$value['job_card_number'];
                $customer_id = $value['customer_id'];
                $sales_tax =$value['sales_tax'];
                $tax_amount = $value['tax_amount'];
                $total_amount = $value['total_amount'];
                // $prefoma_number =$value['prefoma_number'];
                $balance = $value['balance'];
                $cost = $value['cost'];
                $office = $value['office'];

            }
           

             Yii::$app->db->createCommand()

                 ->insert('table_invoice', ['job_card_number'=>$job_card_number, 'customer_id'=>$customer_id, 'cost'=>$cost,'payment'=>$payment,'tax_amount'=>$tax_amount,'sales_tax'=>$sales_tax,'total_amount'=>$total_amount,'balance'=>$balance,'created_at'=>date('Y:m:d H:i:s'), 'created_by'=>\Yii::$app->user->identity->id, 'status'=>11, 'office'=>$office ])

                 ->execute(false);
                
                $data = array();
                $modelInvoice = Invoice::find()->where(['job_card_number'=>$job_card_number])->all();
                foreach ($modelInvoice as  $value) {
                    $invoice_num = $value['id'];
                   // $job_card_num = $value['job_card_number'];
                
                }
                 $modelSub = PrefomaInvoiceSub::find()->where(['prefoma_number'=>$id])->all();
                  foreach ($modelSub as  $value) {
                $quantity = $value['quantity'];
                $service_type = $value['service_type'];
                $description = $value['description'];
                $cost_qty = $value['cost_qty'];
                $discount = $value['discount'];
                $data []= [$invoice_num, $quantity, $service_type, $description, $cost_qty, $discount]; 
            
               
            }
             Yii::$app->db->createCommand()->batchInsert('table_invoice_sub', ['invoice_number','quantity','service_type','description', 'cost_qty', 'discount'] ,$data)->execute();


             $modelsPrefomaDetails = PrefomaInvoiceDetails::find()->where(['prefoma_number'=>$id])->all();
             $modelsPrefomaDetailsTotal = PrefomaInvoiceTotal::find()->where(['prefoma_number'=>$id])->all();

             if(!empty($modelsPrefomaDetails)){
                foreach ($modelsPrefomaDetails as  $value) {
                    //$prefoma_number = $value['prefoma_number'];
                    $items = $value['items'];
                    $quantity = $value['quantity'];
                    $description = $value['description'];
                    $cost_qty = $value['cost_qty'];
                    $totals = $value['totals'];

                    $Prefomadata []=[$invoice_num, $items, $quantity, $description, $cost_qty, $totals]; 
                }
                Yii::$app->db->createCommand()->batchInsert('table_invoice_details', ['invoice_number','items','quantity','description', 'cost_qty','totals'] ,$Prefomadata)->execute();
             }
             if(!empty($modelsPrefomaDetailsTotal)){
                    foreach ($modelsPrefomaDetailsTotal as  $value) {
                        //$prefoma_number = $value['prefoma_number'];
                        $total = $value['total'];
                    }
                      Yii::$app->db->createCommand()

                 ->insert('table_invoice_total', ['invoice_number'=>$invoice_num, 'total'=>$total])

                 ->execute(false);
             }
                


              Yii::$app->db->createCommand()

             ->update('table_prefoma_invoice', ['approval' => 14],  ['id'=> $id])

             ->execute();

             return $this->redirect('index');
        }

    }



    /**
     * Deletes an existing PrefomaInvoice model.
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
     * Finds the PrefomaInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrefomaInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrefomaInvoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
