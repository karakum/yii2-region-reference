<?php

use karakum\region\models\Region;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model karakum\region\models\CreateRegionForm */
/* @var $levels karakum\region\models\RegionLevel[] */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
$('#tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  $('#createregionform-mode').val($(e.target).data('mode'));
})
JS;
$this->registerJs($js);

?>

<div class="region-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'level_id')->dropDownList(ArrayHelper::map($levels, 'id', function ($el) {
                return $el->name ?: $el->type->code;
            })) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'status')->dropDownList(Region::getStatuses()) ?>
        </div>
    </div>

    <div class="tabs-container">
        <?= Nav::widget([
            'options' => ['class' => 'nav-tabs', 'id' => 'tabs'],
            'items' => [
                ['label' => Yii::t('regions/region', 'One item'), 'url' => '#item', 'linkOptions' => ['data' => ['toggle' => 'tab', 'mode' => '0']], 'active' => 'true'],
                ['label' => Yii::t('regions/region', 'Multiple items'), 'url' => '#bulk', 'linkOptions' => ['data' => ['toggle' => 'tab', 'mode' => '1']]],
            ],
        ]) ?>
        <?= $form->field($model, 'mode')->hiddenInput()->label(false) ?>
        <div class="tab-content">
            <div class="tab-pane active" id="item">

                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="tab-pane" id="bulk">
                <?= $form->field($model, 'multiple_data')->textarea(['rows' => 6]) ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('regions/region', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
