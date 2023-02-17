<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var app\models\Appointment $model */
/** @var ActiveForm $form */

$this->title = "Запись к врачу";
?>
<div class="site-appointment">

    <?php $form = ActiveForm::begin(); ?>

    <h2>Запись к врачу</h2>
    <h4>Врач: <?= $model->doctor->user->full_name ?></h4>

    <?= $form->field($model, 'when')->widget(DateTimePicker::class, [
        'options' => ['placeholder' => 'Укажите время приёма...'],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'd-M-y H:i',
            'startDate' => date('Y-m-d H:i:s'),
            'todayHighlight' => true
        ]
    ]) ?>
    <br />

    <div class="form-group">
        <?= Html::submitButton('Записаться', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-appointment -->