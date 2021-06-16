<?php
/**
 * Company.php Management plugin for Craft CMS 3.x
 *
 * A plugin to setup companies
 *
 * @link      http://percipio.london/
 * @copyright Copyright (c) 2021 Percipio
 */
namespace percipiolondon\companymanagement\migrations;
use Craft;
use craft\db\Migration;
use craft\helpers\MigrationHelper;
use percipiolondon\companymanagement\db\Table;
use percipiolondon\companymanagement\elements\Company;
use yii\base\NotSupportedException;
/**
 * Installation Migration
 *
 * @author Percipio Global Ltd.
 * @since 1.0.0
 */
class Install extends Migration {
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();
        // Refresh the db schema caches
        Craft::$app->db->schema->refresh();
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKeys();
        $this->dropTables();
        $this->dropProjectConfig();
        $this->delete(\craft\db\Table::ELEMENTINDEXSETTINGS, ['type' => [ Company::class ]]);
        $this->delete(\craft\db\Table::FIELDLAYOUTS, ['type' => [ Company::class ]]);
    }
    // Protected Functions
    // =========================================================================
    /**
     * Creates the tables for Company.php Management
     */
    public function createTables()
    {
        $tableSchemaCompany = Craft::$app->db->schema->getTableSchema(Table::CM_COMPANIES);
        $tableSchemaUsers = Craft::$app->db->schema->getTableSchema(Table::CM_USERS);
        $tableSchemaTypes = Craft::$app->db->schema->getTableSchema(Table::CM_COMPANYTYPES);
        $tableSchemaDocuments = Craft::$app->db->schema->getTableSchema(Table::CM_DOCUMENTS);

        if ($tableSchemaCompany === null) {
            $this->createTable(Table::CM_COMPANIES, [
                'id' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                // Custom columns in the table
                'siteId' => $this->integer()->notNull()->defaultValue(1),
                'info' => $this->string()->notNull()->defaultValue(''),
                'name' => $this->string()->notNull()->defaultValue(''),
                'slug' => $this->string()->notNull()->defaultValue(''),
                'address' => $this->string()->notNull()->defaultValue(''),
                'town' => $this->string()->notNull()->defaultValue(''),
                'postcode' => $this->string()->notNull()->defaultValue(''),
                'registerNumber' => $this->string()->notNull()->defaultValue(''),
                'payeReference' => $this->string()->notNull()->defaultValue(''),
                'accountsOfficeReference' => $this->string()->notNull()->defaultValue(''),
                'taxReference' => $this->string()->notNull()->defaultValue(''),
                'website' => $this->string()->notNull()->defaultValue(''),
                'logo' => $this->integer(),
                'contactFirstName' => $this->string()->notNull()->defaultValue(''),
                'contactLastName' => $this->string()->notNull()->defaultValue(''),
                'contactEmail' => $this->string()->notNull()->defaultValue(''),
                'contactRegistrationNumber' => $this->string()->notNull()->defaultValue(''),
                'contactPhone' => $this->string(),
                'contactBirthday' => $this->dateTime(),
                'userId' => $this->integer(),
            ]);
        }
        if ($tableSchemaTypes === null) {
            $this->createTable(Table::CM_COMPANYTYPES, [
                'id' => $this->primaryKey(),
                'fieldLayoutId' => $this->integer(),
                'name' => $this->string()->notNull(),
                'handle' => $this->string()->notNull(),
            ]);
        }
        if ($tableSchemaUsers === null) {
            $this->createTable(Table::CM_USERS, [
                'id' => $this->primaryKey(),
                'companyId' => $this->integer(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                // Custom columns in the table
                'userId' => $this->integer(),
                'employeeStartDate' => $this->string(),
                'employeeEndDate' => $this->string(),
                'birthday' => $this->string(),
                'nationalInsuranceNumber' => $this->string()->notNull()->defaultValue(''),
                'grossIncome' => $this->string()->defaultValue(''),
            ]);
        }
        if ($tableSchemaDocuments === null) {
            $this->createTable(Table::CM_DOCUMENTS, [
                'id' => $this->integer()->notNull(),
                'userId' => $this->integer()->notNull(),
                'assetId' => $this->integer()->notNull(),
                'PRIMARY KEY(id)',
            ]);
        }
    }
    /**
     * Drop the tables
     */
    public function dropTables()
    {
        $this->dropTableIfExists(Table::CM_COMPANIES);
        $this->dropTableIfExists(Table::CM_USERS);
        $this->dropTableIfExists(Table::CM_DOCUMENTS);
        return null;
    }
    /**
     * Drop the foreign keys
     */
    public function dropForeignKeys()
    {
        $tables = [
            Table::CM_COMPANIES,
            Table::CM_USERS,
            Table::CM_DOCUMENTS
        ];
        foreach ($tables as $table) {
            $this->_dropForeignKeyToAndFromTable($table);
        }
    }
    /**
     * Deletes the project config entry.
     */
    public function dropProjectConfig()
    {
        Craft::$app->projectConfig->remove('companies');
    }
    /**
     * Creates the indexes.
     */
    public function createIndexes()
    {
//        $this->createIndex(null, Table::CM_COMPANIES, 'typeId', false);
        $this->createIndex(null, Table::CM_COMPANIES, 'id', true);
        $this->createIndex(null, Table::CM_COMPANYTYPES, 'handle', true);
        $this->createIndex(null, Table::CM_COMPANYTYPES, 'fieldLayoutId', true);
//        $this->createIndex(null, Table::CM_USERS, 'companyId', true);
    }
    /**
     * Adds the foreign keys.
     */
    public function addForeignKeys()
    {
        $this->addForeignKey(null, Table::CM_COMPANIES, ['siteId'], \craft\db\Table::SITES, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_COMPANIES, ['id'], \craft\db\Table::ELEMENTS, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_COMPANIES, ['logo'], \craft\db\Table::ASSETS, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_COMPANIES, ['userId'], \craft\db\Table::USERS, ['id'], null, 'CASCADE');
        $this->addForeignKey(null, Table::CM_USERS, ['userId'], \craft\db\Table::USERS, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_USERS, ['companyId'], Table::CM_COMPANIES, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_DOCUMENTS, ['assetId'], \craft\db\Table::ASSETS, ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey(null, Table::CM_DOCUMENTS, ['userId'], \craft\db\Table::USERS, ['id'], 'CASCADE', 'CASCADE');
    }
    /**
     * Returns if the table exists.
     *
     * @param string $tableName
     * @param \yii\db\Migration|null $migration
     * @return bool If the table exists.
     * @throws NotSupportedException
     */
    private function _tableExists(string $tableName): bool
    {
        $schema = $this->db->getSchema();
        $schema->refresh();
        $rawTableName = $schema->getRawTableName($tableName);
        $table = $schema->getTableSchema($rawTableName);
        return (bool)$table;
    }
    /**
     * @param string $tableName
     * @throws NotSupportedException
     */
    private function _dropForeignKeyToAndFromTable(string $tableName)
    {
        if ($this->_tableExists($tableName)) {
            MigrationHelper::dropAllForeignKeysToTable($tableName, $this);
            MigrationHelper::dropAllForeignKeysOnTable($tableName, $this);
        }
    }
}





