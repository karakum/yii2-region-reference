<?php


/* @var $this yii\web\View */
/* @var $model karakum\region\models\RegionLevel */

$this->title = Yii::t('regions/level', 'Create Region Level');
$this->params['breadcrumbs'][] = ['label' => Yii::t('regions/level', 'Region Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-level-create">

    <div class="box box-primary">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
