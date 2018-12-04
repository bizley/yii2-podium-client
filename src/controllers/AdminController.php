<?php

declare(strict_types=1);

namespace bizley\podium\client\controllers;

use bizley\podium\client\enums\Role;
use bizley\podium\client\enums\Setting;
use bizley\podium\client\filters\PodiumAccessControl;

/**
 * Class AdminController
 * @package bizley\podium\client\controllers
 */
class AdminController extends \yii\web\Controller
{
    public $layout = 'admin.twig';

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => PodiumAccessControl::class,
                'podium' => $this->module,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Role::ADMIN],
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

    /**
     * @return string
     */
    public function actionSettings(): string
    {
        return $this->render('settings.twig', [
            'settings' => [
                Setting::NAME => 'text',
                Setting::MEMBERS_VISIBLE => 'radio',
                Setting::MERGE_POSTS => 'radio',
                Setting::MIN_POSTS_FOR_HOT => 'text',
                Setting::POLLS_ALLOWED => 'radio',
            ],
        ]);
    }
}
