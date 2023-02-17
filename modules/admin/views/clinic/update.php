<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Clinic $model */

$this->title = 'Редактирование клиники: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="clinic-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>