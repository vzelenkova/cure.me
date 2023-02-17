<?php

use app\models\DoctorSpecialty;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Doctor $model */

$this->title = $model->user->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Владельцы клиник', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doctor-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model->user,
        'attributes' => [
            'full_name',
            'email',
        ],
    ]) ?>

    <a href="<?= Url::to(["clinic/view", 'id' => $model->clinic->id]) ?>" class="card w-100 shadow-sm" style="padding-left: 16px; text-decoration: none; color: inherit;">
        <div class="d-flex row flex-nowrap align-items-center ">
            <img src="/web/doctor.svg" style="width: 64px; height: 64px">
            <div><?= $model->clinic->name ?></div>
        </div>
    </a>

</div>