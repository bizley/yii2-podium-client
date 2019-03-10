<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\controllers;

use bizley\podium\client\admin\PodiumAdmin;
use bizley\podium\client\enums\Role;
use bizley\podium\client\filters\PodiumAccessControl;
use bizley\podium\client\forms\SettingsForm;
use Yii;
use yii\web\Response;

/**
 * Class CategoriesController
 * @package bizley\podium\client\admin\controllers
 *
 * @property PodiumAdmin $module
 */
class CategoriesController extends \yii\web\Controller
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
                    'label' => Yii::t('podium.client.link', 'admin.dashboard'),
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
        $this->setBreadcrumbs([Yii::t('podium.client.header', 'admin.categories')]);

        return $this->render('categories.twig', [
            'categories' => $this->module->api->category->getCategories(
                null,
                [
                    'defaultOrder' => [
                        'sort' => SORT_ASC
                    ],
                ],
                false
            ),
        ]);
    }

    /**
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSettings()
    {
        $model = new SettingsForm($this->module->getConfig());

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->module->notify->success(Yii::t('podium.client.alert', 'setting.save.success'));
            } else {
                $this->module->notify->danger(Yii::t('podium.client.alert', 'setting.save.error'));
            }

            return $this->refresh();
        }

        return $this->render('settings.twig', [
            'model' => $model,
        ]);
    }
}
