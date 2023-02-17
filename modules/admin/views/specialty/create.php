<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Specialty $model */

$this->title = 'Добавление новой специальности';
$this->params['breadcrumbs'][] = ['label' => 'Специальности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specialty-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
