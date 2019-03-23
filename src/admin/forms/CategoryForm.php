<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\forms;

use bizley\podium\api\models\category\Category;
use bizley\podium\api\Podium;
use bizley\podium\client\base\ApiModelNotFoundException;
use bizley\podium\client\base\ErrorsSummaryTrait;
use bizley\podium\client\base\Notify;
use Yii;
use yii\base\Model;

/**
 * Class CategoryForm
 * @package bizley\podium\client\admin\forms
 */
class CategoryForm extends Model
{
    use ErrorsSummaryTrait;

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
    public $after = -1;

    /**
     * CategoryForm constructor.
     * @param Podium $api
     * @param Notify $notify
     * @param array $categories
     * @param null|int $categoryId
     * @param array $config
     */
    public function __construct(
        Podium $api,
        Notify $notify,
        array $categories,
        ?int $categoryId = null,
        array $config = []
    )
    {
        $this->_api = $api;
        $this->_notify = $notify;
        $this->_categories = $categories;
        $this->_categoryApiId = $categoryId;

        if ($categories) {
            end($categories);
            $this->after = key($categories);
            reset($categories);
        }

        parent::__construct($config);
    }

    /**
     * @throws ApiModelNotFoundException
     */
    public function init(): void
    {
        parent::init();

        $categoryId = $this->getCategoryApiId();

        if ($categoryId !== null) {
            /* @var $category Category */
            $category = $this->getApi()->category->getCategoryById($categoryId);

            if ($category === null) {
                throw new ApiModelNotFoundException("Category {$categoryId} has not been found.");
            }

            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->visible = $category->visible;

            if ($category->sort === 0) {
                $this->after = -1;
            } else {
                $sortOrder = \array_keys($this->getCategories());
                $this->after = $sortOrder[$category->sort - 1];
            }
        }
    }

    private $_api;

    /**
     * @return Podium
     */
    public function getApi(): Podium
    {
        return $this->_api;
    }

    private $_notify;

    /**
     * @return Notify
     */
    public function getNotify(): Notify
    {
        return $this->_notify;
    }

    private $_categories;

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->_categories;
    }

    private $_categoryApiId;

    /**
     * @return int|null
     */
    public function getCategoryApiId(): ?int
    {
        return $this->_categoryApiId;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['visible'], 'default', 'value' => true],
            [['name', 'slug'], 'filter', 'filter' => 'trim'],
            [['after'], 'filter', 'filter' => 'intval'],
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

        if ($this->getCategoryApiId() === null) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * @return bool
     */
    protected function create(): bool
    {
        $response = $this->getApi()->category->create(
            [
                'name' => $this->name,
                'description' => $this->description,
                'slug' => $this->slug ?? null,
                'visible' => $this->visible,
                'sort' => 0,
            ],
            $this->getApi()->member->getMemberByUserId(Yii::$app->user->id)
        );

        if (!$response->result) {
            if ($response->data) {
                $this->getNotify()->error($this->getErrorsSummary(
                    Yii::t('podium.admin.error', 'category.add.summary'),
                    $response->data
                ));
            } else {
                $this->getNotify()->error(Yii::t('podium.admin.error', 'category.add'));
            }

            return false;
        }

        $this->_categoryApiId = $response->data['id'];

        $this->resort();

        return true;
    }

    /**
     * @return bool
     */
    protected function update(): bool
    {
        $response = $this->getApi()->category->edit(CategoryForm::findOne(1)
            [
                'name' => $this->name,
                'description' => $this->description,
                'slug' => $this->slug ?? null,
                'visible' => $this->visible,
                'sort' => 0,
            ],
            $this->getApi()->member->getMemberByUserId(Yii::$app->user->id)
        );

        if (!$response->result) {
            if ($response->data) {
                $this->getNotify()->error($this->getErrorsSummary(
                    Yii::t('podium.admin.error', 'category.add.summary'),
                    $response->data
                ));
            } else {
                $this->getNotify()->error(Yii::t('podium.admin.error', 'category.add'));
            }

            return false;
        }

        $this->_categoryApiId = $response->data['id'];

        $this->resort();

        return true;
    }

    protected function resort(): void
    {
        $sortOrder = \array_keys($this->getCategories());

        if ($this->after === -1) {
            \array_unshift($sortOrder, $this->getCategoryApiId());
        } elseif (!\in_array($this->after, $sortOrder, true)) {
            $sortOrder[] = $this->getCategoryApiId();
        } else {
            \array_splice(
                $sortOrder,
                \array_search($this->after, $sortOrder, true) + 1,
                0,
                [$this->getCategoryApiId()]
            );
        }

        $response = $this->getApi()->category->sort($sortOrder);

        if (!$response->result) {
            if ($response->data) {
                $this->getNotify()->error($this->getErrorsSummary(
                    Yii::t('podium.admin.error', 'category.sort.summary'),
                    $response->data
                ));
            } else {
                $this->getNotify()->error(Yii::t('podium.admin.error', 'category.sort'));
            }
        }
    }
}
