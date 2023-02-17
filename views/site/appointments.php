<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;

/** @var app\models\Appointment[] $models */
/** @var yii\data\Pagination $pages */
$this->title = "Мои записи";
?>

<div class="d-flex row flex-wrap gap-2 mt-2">
    <? foreach ($models as $model) : ?>
        <div class="card w-100 shadow-sm p-2" style="padding-left: 16px; text-decoration: none; color: inherit;">
            <div class="d-flex flex-nowrap align-items-center gap-2">
                <img src="/web/doctor.svg" style="width: 64px; height: 64px">
                <div class="d-flex flex-column">
                    <span>
                        Врач:&nbsp;
                        <a href="<?= Url::to(["doctor", 'id' => $model->doctor_id]) ?>">
                            <?= $model->doctor->user->full_name ?>
                        </a>
                    </span>
                    <div>
                        Дата и время приёма: <span class="badge bg-secondary"><?= date('Y-m-d H:i:s', strtotime($model->when)) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <? endforeach ?>
</div>
<?

foreach ($models as $model) {
    // display $model here
}

?>


<?= LinkPager::widget([
    'pagination' => $pages,
]) ?>