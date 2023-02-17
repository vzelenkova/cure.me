<?php

/** @var yii\web\View $this */

use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var app\models\Doctor[] $models */
/** @var yii\data\Pagination $pages */

$this->title = 'Врачи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-doctors">
    <div class="d-flex justify-content-center col flex-wrap gap-2">
        <? foreach ($models as $model) : ?>
            <a href="<?= Url::to(["doctor", 'id' => $model->id]) ?>" class="card w-100 shadow-sm" style="padding-left: 16px; text-decoration: none; color: inherit;">
                <div class="d-flex row flex-nowrap align-items-center ">
                    <img src="/web/doctor.svg" style="width: 64px; height: 64px">
                    <div><?= $model->user->full_name ?></div>
                </div>
            </a>
        <? endforeach ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]) ?>
</div>