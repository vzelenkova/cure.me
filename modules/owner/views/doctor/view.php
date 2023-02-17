<?php

use app\models\DoctorSpecialty;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;

/** @var yii\web\View $this */
/** @var app\models\Doctor $model */

$this->title = $model->user->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doctor-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?php
    if (!empty($model->avatar)) {
        $img = Yii::getAlias('@webroot') . '/images/doctor/source/' .  $model->avatar;
        if (is_file($img)) {
            $url = Yii::getAlias('@web') . '/images/doctor/source/' .  $model->avatar;
            echo Html::img($url, ['style' => 'width: 200px; height: 200px; object-fit: cover', 'class' => 'py-2']);
        }
    }
    ?>

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
        'model' => $model,
        'attributes' => [
            'description',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model->user,
        'attributes' => [
            'full_name',
            'email',
        ],
    ]) ?>


    <h2>Специальности</h2>
    <div class="list-group">
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getDoctorSpecialties(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_specialty',
        ]);
        ?>
    </div>


    <h2> Клиники </h2>
    <? foreach ($model->clinics as $model) : ?>
        <a href="<?= Url::to(["clinic/view", 'id' => $model->id]) ?>" class="card w-100 shadow-sm" style="padding-left: 16px; text-decoration: none; color: inherit;">
            <div class="d-flex row flex-nowrap align-items-center ">
                <img src="/web/doctor.svg" style="width: 64px; height: 64px">
                <div><?= $model->name ?></div>
            </div>
        </a>
    <? endforeach ?>

</div>