<?php

namespace backend\controllers;

use Yii;
use app\models\Menu;
use app\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\MenuSubcategory;
use yii\helpers\Json;
use app\models\Model;
use yii\helpers\ArrayHelper;
use app\models\Patches\Patch;
use yii\filters\AccessControl;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            if (Yii::$app->request->post('hasEditable')) {
              $menu_id=$_POST['editableKey'];
              $model = Menu::findOne($menu_id);
              $out = Json::encode(['output'=>'', 'message'=>'']);
              $post = [];
              $redirectFlag = false;
              $posted = current($_POST['Menu']);
              $post['Menu'] = $posted;
              if ($model->load($post)) {
                date_default_timezone_set("Africa/Nairobi");
                $model->updated_by  = \Yii::$app->user->identity->id;
                $model->updated_at = date('Y:m:d H:i:s');
               $model->save(false);
                    $output ='me';
                     $out = Json::encode(['output'=>'', 'message'=>'']);
                // \Yii::$app->getSession()->setFlash('success', 'successfully got on to the payment page');
                      Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Status Change successfully.']);
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
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $modelsSubcategory = [new MenuSubcategory];
        
        if ($model->load(Yii::$app->request->post())) {
            $modelsSubcategory = Model::createMultiple(MenuSubcategory::classname());
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
  
            date_default_timezone_set("Africa/Nairobi");
            $model->menu_id = Patch::randomNumbers();
            $model->created_at = date('Y:m:d H:i:s');
            $model->created_by = \Yii::$app->user->identity->id;
            $model->status = 10;
            $active_depat = array();
            $model->menu_active_department = implode(',', $model->menu_active_department);
           
            // $valid = $model->validate();
            // $valid = Model::validateMultiple($modelsPackageRoutes) && $valid;

            
                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    if($flag = $model->save(false)){
                      
                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            if(empty($modelsSubcategorys->subcategory)){

                            }else{
                            $modelsSubcategorys->menu_id = $model->menu_id;
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
                        Yii::$app->session->setFlash('success',['type' => 'success','message'=>'Menu created successfully.']);
                        return $this->redirect(['index']);
                        
                    }
                }
                catch (Exception $e){
                    $transaction->rollBack();
                }
            
        }
        return $this->renderAjax('create', [
            'model' => $model,
            'modelsSubcategory'=>(empty($modelsSubcategory))? [new MenuSubcategory] : $modelsSubcategory
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->menu_active_department = explode(",", $model->menu_active_department);
        $modelsSubcategory = $model->subCategorymenu;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsSubcategory, 'id', 'id');
            $modelsSubcategory = Model::createMultiple(MenuSubcategory::classname(), $modelsSubcategory);
            Model::loadMultiple($modelsSubcategory, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSubcategory, 'id', 'id')));

            $model->menu_active_department = implode(',', $model->menu_active_department);
            

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            MenuSubcategory::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($modelsSubcategory as $modelsSubcategorys) {
                            if(!empty($modelsSubcategorys->subcategory)){
                             $modelsSubcategorys->menu_id = $model->menu_id;
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
            'modelsSubcategory' => (empty($modelsSubcategory)) ? [new MenuSubcategory] : $modelsSubcategory
        ]);
    }

    /**
     * Deletes an existing Menu model.
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
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
