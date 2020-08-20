<?php

namespace backend\controllers;

use Yii;
use app\models\Drivers;
use app\models\DriversSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\models\Patches\Patch;
use yii\filters\AccessControl;

/**
 * DriversController implements the CRUD actions for Drivers model.
 */
class DriversController extends Controller
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
     * Lists all Drivers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DriversSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


      if (Yii::$app->request->post('hasEditable')) {
      $departments_id=$_POST['editableKey'];
      $model = Drivers::findOne($departments_id);
      $out = Json::encode(['output'=>'', 'message'=>'']);
      $post = [];
      $redirectFlag = false;
      $posted = current($_POST['Drivers']);
      $post['Drivers'] = $posted;
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
     * Displays a single Drivers model.
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
     * Creates a new Drivers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Drivers();
        if ($model->load(Yii::$app->request->post())) {
          date_default_timezone_set("Africa/Nairobi");
          $model->table_driver_id =  Patch::randomNumbers();
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
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Updates an existing Drivers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
    }

    /**
     * Deletes an existing Drivers model.
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
     * Finds the Drivers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Drivers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Drivers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
