<?php

use app\models\Specialty;
use pozitronik\widgets\SearchWidget;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <?= SearchWidget::widget([
        'ajaxEndpoint' => 'site/search',
        'models' => [
            'Doctor' => [
                'class' => Doctor::class,
                'template' => '<a href="/web/site/specialty?id={{id}}" class="suggestion-item search-link">{{full_name}}</div><div class="clearfix"></div><div class="clearfix"></div></a>',
                'header' => 'Врачи',
                'limit' => 5,
                'attributes' => [
                    'id',
                    'full_name',
                ]
            ],
            'Specialty' => [
                'class' => Specialty::class,
                'template' => '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="clearfix"></div></div>',
                'header' => 'Специальности',
                'limit' => 5,
                'attributes' => [
                    'name',
                ]
            ],
            'Clinic' => [
                'class' => Clinic::class,
                'template' => '<a href="/web/site/clinic?id={{id}}" class="suggestion-item search-link"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="suggestion-secondary">{{address}}</div><div class="clearfix"></div></a>',
                'header' => 'Клиники',
                'limit' => 5,
                'attributes' => [
                    'name',
                ]
            ],
        ]
    ]) ?>


    <div class="body-content">

        <h2> Популярные клиники </h2>
        <div class="d-flex flex-row p-2 gap-2">
            <? foreach ($clinics as $model) : ?>
                <a href="<?= Url::to(["clinic", 'id' => $model->id]) ?>" class="d-flex flex-column col-4 text-decoration-none" style="color: inherit">
                    <div class="card">
                        <? if ($model->avatar) : ?>

                            <? $model->renderAvatar(['style' => 'object-fit: cover; width: 100%; aspect-ratio: 1; border-radius: 5px 5px 0 0']); ?>
                        <? else : ?>
                            <div class="d-flex flex-row align-items-center justify-content-center" style="width: 100%; aspect-ratio: 1; background-color: lightgray">Изображение не загружено</div>
                        <? endif; ?>
                        <h6 style="margin-left: 4px"><?= $model->name ?></h6>
                    </div>
                </a>
            <? endforeach; ?>
        </div>

        <h2> Популярные врачи </h2>
        <div class="d-flex flex-row p-2 gap-2">
            <? foreach ($doctors as $model) : ?>
                <a href="<?= Url::to(["doctor", 'id' => $model->id]) ?>" class="d-flex flex-column col-4 text-decoration-none" style="color: inherit">
                    <div class="card">
                        <? if ($model->avatar) : ?>

                            <? $model->renderAvatar(['style' => 'object-fit: cover; width: 100%; aspect-ratio: 1; border-radius: 5px 5px 0 0']); ?>
                        <? else : ?>
                            <div class="d-flex flex-row align-items-center justify-content-center" style="width: 100%; aspect-ratio: 1; background-color: lightgray">Изображение не загружено</div>
                        <? endif; ?>
                        <h6 style="margin-left: 4px"><?= $model->user->full_name ?></h6>
                    </div>
                </a>
            <? endforeach; ?>
        </div>

    </div>
</div>