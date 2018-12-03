<?php

declare(strict_types=1);

namespace bizley\podium\client\controllers;

/**
 * Class MainController
 * @package bizley\podium\client\controllers
 */
class MainController extends \yii\web\Controller
{
    public $layout = 'default.twig';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index.twig');
    }
}
