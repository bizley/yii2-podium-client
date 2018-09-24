<?php

declare(strict_types=1);

namespace bizley\podium\client\controllers;

/**
 * Class AdminController
 * @package bizley\podium\client\controllers
 */
class AdminController extends \yii\web\Controller
{
    public $layout = 'admin';

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
        return $this->render('index');
    }
}
