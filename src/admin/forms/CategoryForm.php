<?php

declare(strict_types=1);

namespace bizley\podium\client\admin\forms;

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
    public $visible;

    /**
     * @var int
     */
    public $after;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['visible'], 'default', 'value' => true],
            [['name', 'slug'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['visible'], 'boolean'],
            [['sort'], 'integer'],
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

        return true;
    }
}
