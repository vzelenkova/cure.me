<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Clinic $model */

$this->title = 'Добавление клиники';
$this->params['breadcrumbs'][] = ['label' => 'Клиники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clinic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>