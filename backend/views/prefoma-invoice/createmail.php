<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JobCard */

$this->title = 'Create Mail';
$this->params['breadcrumbs'][] = ['label' => 'Job Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-card-create">

<h4 style="border-bottom:1px dotted black; width: 100px;"><strong>Create Mail</strong></h4>

    <?= $this->render('_mailing', [
        'model' => $model,
        'modelMail'=>$modelMail,
          
          
    ]) ?>

</div>
