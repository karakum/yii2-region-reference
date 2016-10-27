<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render('header.php') ?>

    <aside class="main-sidebar">

        <section class="sidebar">
            <?= dmstr\widgets\Menu::widget([
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Home', 'url' => Yii::$app->homeUrl],
                    ['label' => Yii::t('regions', 'Region reference module'), 'options' => ['class' => 'header']],
                    ['label' => Yii::t('regions', 'Regions'), 'icon' => 'fa fa-map', 'url' => ['region/index'], 'active' => (strpos($this->context->route, '/region/') !== false)],
                    ['label' => Yii::t('regions', 'Region levels'), 'icon' => 'fa fa-list', 'url' => ['level/index'], 'active' => (strpos($this->context->route, '/level/') !== false)],
                    ['label' => Yii::t('regions', 'Region types'), 'icon' => 'fa fa-globe', 'url' => ['type/index'], 'active' => (strpos($this->context->route, '/type/') !== false)],
                    ['label' => Yii::t('regions', 'Import'), 'icon' => 'fa fa-upload', 'url' => ['exchange/import']],
                    ['label' => Yii::t('regions', 'Export'), 'icon' => 'fa fa-download', 'url' => ['exchange/export']],
                ],
            ]) ?>

        </section>

    </aside>

    <?= $this->render('content.php', [
        'content' => $content,
    ]) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
