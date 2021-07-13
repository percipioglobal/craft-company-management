<?php


namespace percipiolondon\companymanagement\elements;


use craft\base\Element;
use craft\elements\db\ElementQueryInterface;
use craft\elements\db\UserQuery;

class Employee extends Element
{

    /**
     * Employee Statusses
     */

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @var DateTime
     */
    public $postDate;

    /**
     * @var DateTime
     */
    public $expiryDate;

    /**
     * @var Int
     */
    public $userId;

    /**
     * @var Int
     */

    public $companyId;

    /**
     * @var DateTime
     */
    public $joinDate;

    /**
     * @var DateTime
     */
    public $endDate;

    /**
     * @var String
     */
    public $probationPeriod;

    /**
     * @var String
     */
    public $noticePeriod;

    /**
     * @var String
     */
    public $firstName;

    /**
     * @var String
     */
    public $middleName;

    /**
     * @var String
     */
    public $lastName;

    /**
     * @var String
     */
    public $nameTitle;

    /**
     * @var String
     */
    public $knownAs;

    /**
     * @var String
     */
    public $reference;

    /**
     * @var DateTime
     */
    public $dateOfBirth;

    /**
     * @var String
     */
    public $gender;

    /**
     * @var String
     */
    public $nationality;

    /**
     * @var String
     */
    public $ethnicity;

    /**
     * @var String
     */
    public $maritalStatus;

    /**
     * @var String
     */
    public $nationalInsuranceNumber;

    /**
     * @var String
     */
    public $drivingLicense;

    /**
     * @var String
     */
    public $personalEmail;

    /**
     * @var String
     */
    public $personalMobile;

    /**
     * @var String
     */
    public $personalPhone;

    /**
     * @var ArrayObject
     */
    public $address;

    /**
     * @var String
     */
    public $jobTitle;

    /**
     * @var String
     */
    public $department;

    /**
     * @var String
     */
    public $contractType;

    /**
     * @var String
     */
    public $directDialingIn;

    /**
     * @var String
     */
    public $workExtension;

    /**
     * @var String
     */
    public $workMobile;

    public function init()
    {
        parent::init();
    }

    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('company-management', 'Employee');
    }

    /**
     * @inheritdoc
     */
    public static function lowerDisplayName(): string
    {
        return Craft::t('company-management', 'employee');
    }

    /**
     * @inheritdoc
     */
    public static function pluralDisplayName(): string
    {
        return Craft::t('company-management', 'Employee');
    }

    /**
     * @inheritdoc
     */
    public static function pluralLowerDisplayName(): string
    {
        return Craft::t('company-management', 'employees');
    }

    /**
     * @inheritdoc
     */
    public static function refHandle()
    {
        return 'employee';
    }

    /**
     * Returns whether elements of this type will be storing any data in the `content`
     * table (tiles or custom fields).
     *
     * @return bool Whether elements of this type will be storing any data in the `content` table.
     */
    public static function hasContent(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function hasUris(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function hasStatuses(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE => Craft::t('company-management', 'Active'),
            self::STATUS_INACTIVE => Craft::t('company-management', 'Inactive'),
        ];
    }

    /**
     * @return bool
     */
    public static function isLocalized(): bool
    {
        return true;
    }

    /**
     * Creates an [[ElementQueryInterface]] instance for query purpose.
     *
     * The returned [[ElementQueryInterface]] instance can be further customized by calling
     * methods defined in [[ElementQueryInterface]] before `one()` or `all()` is called to return
     * populated [[ElementInterface]] instances. For example,
     *
     * ```php
     * // Find the entry whose ID is 5
     * $entry = Entry::find()->id(5)->one();
     *
     * // Find all assets and order them by their filename:
     * $assets = Asset::find()
     *     ->orderBy('filename')
     *     ->all();
     * ```
     *
     * If you want to define custom criteria parameters for your elements, you can do so by overriding
     * this method and returning a custom query class. For example,
     *
     * ```php
     * class Product extends Element
     * {
     *     public static function find()
     *     {
     *         // use ProductQuery instead of the default ElementQuery
     *         return new ProductQuery(get_called_class());
     *     }
     * }
     * ```
     *
     * You can also set default criteria parameters on the ElementQuery if you don’t have a need for
     * a custom query class. For example,
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *     public static function find()
     *     {
     *         return parent::find()->limit(50);
     *     }
     * }
     * ```
     *
     * @return ElementQueryInterface The newly created [[ElementQueryInterface]] instance.
     */

    public static function find(): ElementQueryInterface
    {
        return new UserQuery(static::class);
    }

    /**
     * Defines the sources that elements of this type may belong to.
     *
     * @param string|null $context The context ('index' or 'modal').
     *
     * @return array The sources.
     * @see sources()
     */
    protected static function defineSources(string $context = null): array
    {
        $ids = self::_getEmployeeIds();
        return [
            [
                'key' => '*',
                'label' => 'All Employees',
                'defaultSort' => ['firstName', 'desc'],
                'criteria' => ['id' => $ids],
            ]
        ];
    }

    /**
     * @return array
     */
    private static function _getEmployeeIds(): array
    {
        $employeeIds = [];

        // Fetch all employee UIDs
        // @TODO: only select based on company ID
        $employees = (new Query())
            ->from('{{%companymanagement_employees}}')
            ->select('*')
            ->all();

        foreach ($employees as $employee) {
            $employeeIds[] = $employee['id'];
        }

        return $employeeIds;
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        // @TODO: Create additional rules
        $rules = parent::defineRules();
        $rules[] = [['nameTitle', 'firstName', 'lastName', 'dateOfBirth', 'nationalInsuranceNumber'], 'required'];

        return $rules;
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function getIsEditable(): bool
    {
        return true;
    }

    // Events
    // -------------------------------------------------------------------------

    /**
     * Performs actions before an element is saved.
     *
     * @param bool $isNew Whether the element is brand new
     *
     * @return bool Whether the element should be saved
     */
    public function beforeSave(bool $isNew): bool
    {
        return true;
    }

    /**
     * Performs actions after an element is saved.
     *
     * @param bool $isNew Whether the element is brand new
     *
     * @return void
     */
    public function afterSave(bool $isNew)
    {
        if (!$this->propagating) {

            $this->_saveRecord($isNew);
        }

        return parent::afterSave($isNew);
    }

    /**
     * Performs actions before an element is deleted.
     *
     * @return bool Whether the element should be deleted
     */
    public function beforeDelete(): bool
    {
        return true;
    }

    /**
     * @param $isNew
     * @throws Exception
     */
    private function _saveRecord($isNew)
    {
        if (!$isNew) {
            $record = EmployeeRecord::findOne($this->id);

            if (!$record) {
                throw new Exception('Invalid employee ID: ' . $this->id);
            }
        } else {
            $record = new EmployeeRecord();
            $record->id = (int)$this->id;
        }

        $record->firstName = $this->firstName;
        $record->lastName = $this->lastName;
        $record->nameTitle = $this->nameTitle;
        $record->nationalInsuranceNumber = $this->nationalInsuranceNumber;
        $record->dateOfBirth = $this->dateOfBirth;
    }

}