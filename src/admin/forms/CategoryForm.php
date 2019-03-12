<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\forms;

use bizley\podium\api\Podium;
use bizley\podium\client\base\Notify;
use Yii;
use yii\base\Model;

/**
 * Class CategoryForm
 * @package bizley\podium\client\admin\forms
 */
class CategoryForm extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $visible = true;

    /**
     * @var int
     */
    public $after;

    private $api;
    private $notify;

    /**
     * CategoryForm constructor.
     * @param Podium $api
     * @param Notify $notify
     * @param array $config
     */
    public function __construct(Podium $api, Notify $notify, array $config = [])
    {
        $this->api = $api;
        $this->notify = $notify;

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['visible'], 'default', 'value' => true],
            [['name', 'slug'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['visible'], 'boolean'],
            [['after'], 'integer'],
            [['slug'], 'match', 'pattern' => '/^[a-zA-Z0-9\-]{0,255}$/'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('podium.admin.label', 'category.name'),
            'description' => Yii::t('podium.admin.label', 'category.description'),
            'slug' => Yii::t('podium.admin.label', 'category.slug'),
            'visible' => Yii::t('podium.admin.label', 'category.visible'),
            'after' => Yii::t('podium.admin.label', 'category.after'),
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $response = $this->api->category->create(
            [],
            $this->api->member->getMemberByUserId(Yii::$app->user->id)
        );

        if (!$response->result) {
            $this->notify->error('aaa');

            return false;
        }

        return true;
    }
}
