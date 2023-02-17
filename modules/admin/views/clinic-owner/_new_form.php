<?php

use app\models\Clinic;
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

    <? print_r($model); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'full_name')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

    <?
    $items = [];
    $clinics = Clinic::find()->all();
    foreach ($clinics as $clinic) {
        $items[$clinic->id] = $clinic->name;
    }

    // Multiple select without model
    echo '<label> Клиника';
    echo Select2::widget([
        'name' => 'NewClinicOwnerForm[clinic_id]',
        'value' => '',
        'data' => $items,
        'options' => ['placeholder' => 'Select specialties...', 'style' => 'width: 100%'],
    ]);
    echo '</label>';
    ?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>