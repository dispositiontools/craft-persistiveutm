<?php
/**
 * persistiveutm plugin for Craft CMS 3.x
 *
 * Persist UTM values for tracking conversation rates
 *
 * @link      https://www.disposition.tools
 * @copyright Copyright (c) 2022 Disposition Tools
 */

namespace dispositiontools\persistiveutm\migrations;

use dispositiontools\persistiveutm\Persistiveutm;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * persistiveutm Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Disposition Tools
 * @package   Persistiveutm
 * @since     0.0.1
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // persistiveutm_utmtracking table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%persistiveutm_utmtracking}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%persistiveutm_utmtracking}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                // Custom columns in the table
                    'siteId' => $this->integer()->notNull(),
                    'elementId' => $this->integer()->notNull(),
                    'utmCampaign' => $this->string(255)->defaultValue(null),
                    'utmSource' => $this->string(255)->defaultValue(null),
                    'utmMedium' => $this->string(255)->defaultValue(null),
                    'referrer' => $this->string(255)->defaultValue(null),
                    'landingPageUrl' => $this->string(255)->defaultValue(null),
                    'pageUrl' => $this->string(255)->defaultValue(null),
                    'elementType' => $this->string(255)->defaultValue(null),
                    'typeId' => $this->integer()->defaultValue(null),


                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the indexes needed for the Records used by the plugin
     *
     * @return void
     */
    protected function createIndexes()
    {
    // persistiveutm_utmtracking table
        $this->createIndex(
            $this->db->getIndexName(
                '{{%persistiveutm_utmtracking}}',
                'elementId',
                true
            ),
            '{{%persistiveutm_utmtracking}}',
            'elementId',
            true
        );


    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
    // persistiveutm_utmtracking table
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%persistiveutm_utmtracking}}', 'siteId'),
            '{{%persistiveutm_utmtracking}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%persistiveutm_utmtracking}}', 'elementId'),
            '{{%persistiveutm_utmtracking}}',
            'elementId',
            '{{%elements}}',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // persistiveutm_utmtracking table
        $this->dropTableIfExists('{{%persistiveutm_utmtracking}}');

    }
}
