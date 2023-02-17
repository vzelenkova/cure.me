<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Doctor $model */

$this->title = $model->user->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Врачи', 'url' => Url::to("doctors")];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-doctor d-flex flex-column gap-2">
    <div class="d-flex flex-row" style="gap: 8px">
        <div class="d-flex flex-column" style="gap: 8px">
            <? $model->renderAvatar(['style' => 'width: 400px; height: 400px; object-fit: cover']); ?>
            <div class="d-flex flex-column" style="gap: 8px">
                <?= Html::a("Записаться на приём", Url::to(["appointment", 'doctor' => $model->id]), ['class' => 'btn btn-info text-light']) ?>
                <?= Html::a("Записаться на онлайн консультацию", Url::to(["consult", 'doctor' => $model->id]), ['class' => 'btn btn-info text-light']) ?>
                <?= Html::a("Открыть чат с врачом", Url::to(["chat", 'doctor' => $model->id]), ['class' => 'btn btn-info text-light']) ?>
            </div>
        </div>
        <div class="d-flex flex-column" style="gap: 12px">
            <h1><?= $model->user->full_name ?></h1>
            <div class="d-flex gap-2">
                <? foreach ($model->doctorSpecialties as $ds) : ?>
                    <div class="badge bg-secondary badge-pill badge-info"><?= $ds->specialty->name ?></div>
                <? endforeach ?>
            </div>
            <?php if ($model->description) : ?>
                <div class="card p-2" style="min-height: 200px;">
                    <?= $model->description ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


</div>