<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\bizley\podium\client\assets\PodiumAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('nav') ?>

<div class="container">
    <div class="row row-offcanvas row-offcanvas-right">
        <?= $content ?>
        <?= $this->render('admin-menu') ?>
    </div>

    <?= $this->render('footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
