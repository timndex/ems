<?php

namespace backend\controllers;

use Yii;
use app\models\Tax;
use app\models\TaxSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use lo\modules\noty\Wrapper;
use app\models\Patches\Patch;
use yii\filters\AccessControl;

/**
 * TaxController implements the CRUD actions for Tax model.
 */
class TaxController extends Controller
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
     * Lists all Tax models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      if (Yii::$app->request->post('hasEditable')) {
      $departments_id=$_POST['editableKey'];
      $model = Tax::findOne($departments_id);
      $out = Json::encode(['output'=>'', 'message'=>'']);
      $post = [];
      $redirectFlag = false;
      $posted = current($_POST['Tax']);
      $post['Tax'] = $posted;
      if ($model->load($post)) {
        date_default_timezone_set("Africa/Nairobi");
        $model->updated_by  = \Yii::$app->user->identity->id;
        $model->updated_at = date('Y:m:d H:i:s');
       $model->save(false);
            $output ='me';
             $out = Json::encode(['output'=>'', 'message'=>'']);
        // \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
              Yii::$app->session->setFlash('success', 'Successfully');
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
     * Displays a single Tax model.
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
     * Creates a new Tax model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
         $model = new Tax();
            if ($model->load(Yii::$app->request->post())) {
                date_default_timezone_set("Africa/Nairobi");
                $model->id = Patch::randomNumbers();
                $model->created_at = date('Y:m:d H:i:s');
                $model->created_by = \Yii::$app->user->identity->id;
                $model->status = 9; 
                //$model->save();

                if ($model->save(false)) {
                   
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                    }

                               
                }

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

    }

    /**
     * Updates an existing Tax model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

         if ($model->load(Yii::$app->request->post())) {
                date_default_timezone_set("Africa/Nairobi");
                $model->updated_at = date('Y:m:d H:i:s');
                $model->updated_by = \Yii::$app->user->identity->id;
                //$model->status = 9; 
                //$model->save();

                if ($model->save(false)) {
                   
                    if (Yii::$app->request->isAjax) {
                        // JSON response is expected in case of successful save
                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $this->redirect(['index']); 
                         
                    }

                   Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Update successfully.']);            
                }

            }

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Deletes an existing Tax model.
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
     * Finds the Tax model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tax the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tax::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
