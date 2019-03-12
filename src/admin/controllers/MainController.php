<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\controllers;

use bizley\podium\client\admin\PodiumAdmin;
use bizley\podium\client\enums\Role;
use bizley\podium\client\filters\PodiumAccessControl;
use Yii;

/**
 * Class MainController
 * @package bizley\podium\client\admin\controllers
 *
 * @property PodiumAdmin $module
 */
class MainController extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => PodiumAccessControl::class,
                'podium' => $this->module->module,
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
     * @param array $links
     * @param bool $parent
     */
    public function setBreadcrumbs(array $links = [], bool $parent = true): void
    {
        $this->view->params['breadcrumbs'] = array_merge(
            $parent ? [
                [
                    'label' => Yii::t('podium.admin.link', 'dashboard'),
                    'url' => ['admin/index'],
                ]
            ] : [],
            $links
        );
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $this->setBreadcrumbs([Yii::t('podium.admin.header', 'dashboard')], false);

        return $this->render('index.twig');
    }
}
