<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JobCard */

$this->title = 'Update Job Card: ' . $model->job_card_number;
$this->params['breadcrumbs'][] = ['label' => 'Job Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="job-card-update">



    <?= $this->render('_form', [
        'model' => $model,
        'modelsSubcategory' => (empty($modelsSubcategory)) ? [new JobCardSub] : $modelsSubcategory
    ]) ?>

</div>
