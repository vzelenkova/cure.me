<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Doctor $model */

$this->title = 'Добавить владельца клиники';
$this->params['breadcrumbs'][] = ['label' => 'Владельцы клиник', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_new_form', [
        'model' => $model,
    ]) ?>

</div>
