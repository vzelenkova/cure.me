<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Doctor $model */

$this->title = 'Редактировать врача: ' . $model->user->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->full_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="doctor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_edit_form', [
        'model' => $model,
    ]) ?>

</div>