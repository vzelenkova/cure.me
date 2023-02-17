<?php

use app\models\Specialty;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use xtarantulz\preview\PreviewAsset;

PreviewAsset::register($this);

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
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
                echo $form->field($model, 'remove')->checkbox();
            }
        }
        ?>
    </fieldset>

    <?= $form->field($model, 'full_name')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?
    $items = [];
    $specialties = Specialty::find()->all();
    foreach ($specialties as $specialty) {
        $items[$specialty->id] = $specialty->name;
    }

    // Multiple select without model
    echo '<label> Специальности врача';
    echo Select2::widget([
        'name' => 'NewDoctorForm[specialties][]',
        'value' => '',
        'data' => $items,
        'options' => ['multiple' => true, 'placeholder' => 'Select specialties...', 'style' => 'width: 100%'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]);
    echo '</label>';
    ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>