<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="region-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box-body">

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('regions/type', 'Create') : Yii::t('regions/type', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
