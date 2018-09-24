<?php

use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-fixed-top navbar-inverse',
    ],
]);
echo \yii\bootstrap\Nav::widget([
    'options' => ['class' => 'nav navbar-nav'],
    'items' => [
        [
            'label' => 'Home',
            'url' => ['/site/index'],
        ],
        [
            'label' => Yii::t('podium.client.label','nav.administration'),
            'url' => ['admin/index'],
        ],
    ],
]);
NavBar::end();
