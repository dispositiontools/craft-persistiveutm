<?php

namespace dispositiontools\persistiveutm\migrations;

use Craft;
use craft\db\Migration;

/**
 * m220430_084717_persistive_extra_fields migration.
 */
class m220430_084717_persistive_extra_fields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...

        $table = '{{%persistiveutm_utmtracking}}';

        if (!$this->db->columnExists($table, 'utmContent')) {
            $this->addColumn($table, 'utmContent', $this->string(255)->defaultValue(NULL)->after('utmMedium'));
        }
        if (!$this->db->columnExists($table, 'utmTerm')) {
            $this->addColumn($table, 'utmTerm', $this->string(255)->defaultValue(NULL)->after('utmContent'));
        }
        if (!$this->db->columnExists($table, 'userId')) {
            $this->addColumn($table, 'userId', $this->integer()->defaultValue(NULL)->after('utmTerm'));
        }
        if (!$this->db->columnExists($table, 'parsedStatus')) {
            $this->addColumn($table, 'parsedStatus', $this->string(15)->defaultValue('pending')->after('typeId'));
        }
        if (!$this->db->columnExists($table, 'channel')) {
            $this->addColumn($table, 'channel', $this->string(30)->defaultValue(NULL)->after('parsedStatus'));
        }
        if (!$this->db->columnExists($table, 'referrerDomain')) {
            $this->addColumn($table, 'referrerDomain', $this->string(255)->defaultValue(NULL)->after('channel'));
        }


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m220430_084717_persistive_extra_fields cannot be reverted.\n";
        return false;
    }
}
