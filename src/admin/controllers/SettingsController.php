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
class SettingsController extends \yii\web\Controller
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
                    'label' => Yii::t('podium.client.link', 'admin.dashboard'),
                    'url' => ['main/index'],
                ]
            ] : [],
            $links
        );
    }

    /**
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
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

        return $this->render('index.twig', [
            'model' => $model,
        ]);
    }
}
