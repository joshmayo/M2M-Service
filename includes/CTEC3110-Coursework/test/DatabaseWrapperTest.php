<?php
/**
 * DatabaseWrapper.php
 *
 * Unit Tests for DatabaseWrapper class
 *
 * @uses \M2MConnect\DatabaseWrapper
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 */

namespace M2MConnect;

use PHPUnit\Framework\TestCase;


class DatabaseWrapperTest extends TestCase
{
    protected $config;
    protected $test_db_config;



    protected function setUp(): void
    {
        $this->config = require_once("../app/settings.php");
        $this->test_db_config = (TEST_DB_SETTINGS['pdo_test_settings']);


    }

    public function test__construct()

    {
        $testDbWrapper = new DatabaseWrapper();

        $this->assertInstanceOf(DatabaseWrapper::class,$testDbWrapper);
        $this->assertNull($testDbWrapper->getVars()[0]);
        $this->assertNull($testDbWrapper->getVars()[1]);
        $this->assertNull($testDbWrapper->getVars()[2]);
        $this->assertNull($testDbWrapper->getVars()[3]);
        $this->assertEmpty($testDbWrapper->getVars()[4]);
        $this->assertIsObject($testDbWrapper->getVars()[5]);

        var_dump($testDbWrapper->getVars());
    }
    public function testSetDatabaseConnectionSettings()
    {
        $testDbWrapper = new DatabaseWrapper();

        $this->assertNull($testDbWrapper->setDatabaseConnectionSettings($this->test_db_config));

    }
    public function testMakeDatabaseConnection()
    {
        $testDbWrapper = new DatabaseWrapper();

        $testDbWrapper->setDatabaseConnectionSettings($this->test_db_config);

        $this->assertEmpty($testDbWrapper->makeDatabaseConnection());
    }

    public function testGetMessages()
    {
        $testDbWrapper = new DatabaseWrapper();
        $testDbWrapper->setDatabaseConnectionSettings($this->test_db_config);

        $this->assertIsArray($testDbWrapper->getMessages());

        var_dump($testDbWrapper->getMessages());
    }

    public function testAddMessage()
    {
        $testDbWrapper = new DatabaseWrapper();
        $testDbWrapper->setDatabaseConnectionSettings($this->test_db_config);

        $testMessage = new Message("447817814149","447817814149",1,0,
            1,0,1,60,1,"01/01/2019 15:00:10" );

        var_dump($testDbWrapper->addMessage($testMessage));

        $this->assertIsArray($testDbWrapper->addMessage($testMessage));
    }
}
