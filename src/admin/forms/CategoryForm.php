<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\forms;

use bizley\podium\api\Podium;
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
     * @param array $config
     */
    public function __construct(Podium $api, Notify $notify, array $categories, array $config = [])
    {
        $this->_api = $api;
        $this->_notify = $notify;
        $this->_categories = $categories;

        if ($categories) {
            end($categories);
            $this->after = key($categories);
            reset($categories);
        }

        parent::__construct($config);
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

    private $categoryApiId;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->categoryApiId;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

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

        $this->categoryApiId = $response->data['id'];

        $this->resort();

        return true;
    }

    protected function resort(): void
    {
        $sortOrder = \array_keys($this->getCategories());

        if ($this->after === -1) {
            \array_unshift($sortOrder, $this->getId());
        } elseif (!\in_array($this->after, $sortOrder, true)) {
            $sortOrder[] = $this->getId();
        } else {
            \array_splice(
                $sortOrder,
                \array_search($this->after, $sortOrder, true) + 1,
                0,
                [$this->getId()]
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
