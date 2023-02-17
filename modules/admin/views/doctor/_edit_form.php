<?php

use app\models\Specialty;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\NewDoctorForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="doctor-form d-flex flex-col">
    <?php $form = ActiveForm::begin(); ?>

    <fieldset>
        <legend>Загрузить аватар</legend>
        <?= $form->field($model, 'avatar')->fileInput(); ?>
        <?php
        if (!empty($model->avatar)) {
            $img = Yii::getAlias('@webroot') . '/images/doctor/source/' .  $model->avatar;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/doctor/source/' .  $model->avatar;
                echo Html::img($url, ['style' => 'width: 200px; height: 200px; object-fit: cover', 'class' => 'py-2']);
            }
        }
        ?>
    </fieldset>

    <?= $form->field($model->user, 'full_name')->textInput() ?>

    <?= $form->field($model->user, 'email')->textInput() ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?
    $items = [];
    $specialties = Specialty::find()->all();
    foreach ($specialties as $specialty) {
        $items[$specialty->id] = $specialty->name;
    }

    echo '<label> Специальности врача';
    echo Select2::widget([
        'name' => 'specialties[]',
        'value' => array_column($model->doctorSpecialties, 'id'),
        'data' => $items,
        'options' => ['multiple' => true, 'placeholder' => 'Select specialties...'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]);
    echo '</label>';
    ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>