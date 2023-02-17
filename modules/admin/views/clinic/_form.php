<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Clinic $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="clinic-form">

    <?php $form = ActiveForm::begin(); ?>

    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'avatar')->fileInput(); ?>
        <?php
        if (!empty($model->avatar)) {
            $img = Yii::getAlias('@webroot') . '/images/clinic/source/' .  $model->avatar;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/clinic/source/' .  $model->avatar;
                echo Html::img($url, ['style' => 'width: 200px; height: 200px; object-fit: cover', 'class' => 'py-2']);
            }
        }
        ?>
    </fieldset>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'geo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>