<?php

namespace percipiolondon\companymanagement\models;

use Craft;
use craft\base\Field;
use craft\base\Model;
use craft\validators\HandleValidator;
use percipiolondon\companymanagement\CompanyManagement;
use yii\helpers\ArrayHelper;
use yii\validators\UniqueValidator;
use percipiolondon\companymanagement\records\Company as CompanyTypeRecord;

class CompanyType extends Model
{
    /**
     * @var int|null ID
     */
    public $id;

    /**
     * @var int|null Field layout ID
     */
    public $fieldLayoutId;

    /**
     * @var string|null Name
     */
    public $name;

    /**
     * @var string|null Handle
     */
    public $handle;

    /**
     * @var bool Has title field
     */
    public $hasTitleField = true;

    /**
     * @var string|null Title format
     */
    public $titleFormat;

    /**
     * @var string UID
     */
    public $uid;

    /**
     * @var string Template
     */
    public $template;

    /**
     * @var CompanyTypeSite[]
     */
    private $_siteSettings;

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        $rules = parent::defineRules();
//        $rules[] = [['id', 'fieldLayoutId'], 'number', 'integerOnly' => true];
//        $rules[] = [['name', 'handle'], 'required'];
//        $rules[] = [['name', 'handle'], 'string', 'max' => 255];
//        $rules[] = [
//            ['handle'],
//            HandleValidator::class,
//            'reservedWords' => ['id', 'dateCreated', 'dateUpdated', 'uid', 'title'],
//        ];
//        $rules[] = [
//            ['name'],
//            UniqueValidator::class,
//            'targetClass' => CompanyTypeRecord::class,
//            'targetAttribute' => ['name'],
//            'comboNotUnique' => Craft::t('yii', '{attribute} "{value}" has already been taken.'),
//        ];
//        $rules[] = [
//            ['handle'],
//            UniqueValidator::class,
//            'targetClass' => CompanyTypeRecord::class,
//            'targetAttribute' => ['handle'],
//            'comboNotUnique' => Craft::t('yii', '{attribute} "{value}" has already been taken.'),
//        ];
//
//        if (!$this->hasTitleField) {
//            $rules[] = [['titleFormat'], 'required'];
//        }

        return $rules;
    }


    /**
     * Use the handle as the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->handle ?: static::class;
    }

    /**
     * Returns the product types's site-specific settings.
     *
     * @return CompanyTypeSite[]
     */
    public function getSiteSettings(): array
    {
        if ($this->_siteSettings !== null) {
            return $this->_siteSettings;
        }

        if (!$this->id) {
            return [];
        }

        $this->setSiteSettings(ArrayHelper::index(CompanyManagement::$plugin->companyTypes()->getProductTypeSites($this->id), 'siteId'));

        return $this->_siteSettings;
    }

    /**
     * Sets the product type's site-specific settings.
     *
     * @param CompanyTypeSite[] $siteSettings
     */
    public function setSiteSettings(array $siteSettings)
    {
        $this->_siteSettings = $siteSettings;

        foreach ($this->_siteSettings as $settings) {
            $settings->setCompanyType($this);
        }
    }
}