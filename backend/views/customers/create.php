<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */

$this->title = 'Create Customers';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-create">

  <!--  -->

    <?= $this->render('_form', [
        'model' => $model,
         'modelsMail'=>$modelsMail
    ]) ?>

</div>
