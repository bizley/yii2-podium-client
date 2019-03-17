<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\services;

use bizley\podium\api\Podium;
use bizley\podium\client\base\ErrorsSummaryTrait;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class CategorySort
 * @package bizley\podium\client\admin\services
 */
class CategorySort extends BaseObject
{
    use ErrorsSummaryTrait;

    /**
     * CategorySort constructor.
     * @param Podium $api
     * @param array $config
     */
    public function __construct(Podium $api, array $config = [])
    {
        $this->_api = $api;
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

    private $_categories;

    /**
     * @return array
     */
    public function getCategories(): array
    {
        if ($this->_categories === null) {
            $this->_categories = \array_keys(ArrayHelper::map(
                $this
                    ->getApi()
                    ->category
                    ->getCategories(
                        null,
                        [
                            'defaultOrder' => [
                                'sort' => SORT_ASC
                            ],
                        ],
                        false
                    )
                    ->getModels(),
                'id',
                'name'
            ));
        }

        return $this->_categories;
    }

    private $_newIndex;

    /**
     * @param int $index
     */
    public function setNewIndex(int $index): void
    {
        $this->_newIndex = $index;
    }

    /**
     * @return int
     */
    public function getNewIndex(): int
    {
        return $this->_newIndex;
    }

    private $_oldIndex;

    /**
     * @param int $index
     */
    public function setOldIndex(int $index): void
    {
        $this->_oldIndex = $index;
    }

    /**
     * @return int
     */
    public function getOldIndex(): int
    {
        return $this->_oldIndex;
    }

    /**
     * @return bool
     */
    public function reIndex(): bool
    {
        $categoriesInOrder = $this->getCategories();
        $categoriesCount = \count($categoriesInOrder);

        $newIndex = $this->getNewIndex();
        $oldIndex = $this->getOldIndex();

        if ($newIndex < 0 || $oldIndex < 0 || $newIndex > $categoriesCount || $oldIndex > $categoriesCount) {
            return false;
        }

        $extracted = \array_splice($categoriesInOrder, $oldIndex, 1);
        \array_splice($categoriesInOrder, $newIndex, 0, $extracted);

        $response = $this->getApi()->category->sort($categoriesInOrder);

        if (!$response->result) {
            Yii::error(['category.reindex.error', $response->data], 'podium');

            return false;
        }

        return true;
    }
}
