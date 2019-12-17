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


    protected function setUp(): void
    {
        $this->config = require("../app/settings.php");

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

        $this->assertNull($testDbWrapper->setDatabaseConnectionSettings(true));

        var_dump($testDbWrapper->getVars()[0]);

    }
    public function testMakeDatabaseConnection()
    {
        $testDbWrapper = new DatabaseWrapper();



    }

    public function testSafeQuery ()
    {

    }

    public function testGetMessages()
    {


    }


    public function testSafeFetchArray()
    {

    }

    public function testDeleteUser()
    {

    }

    public function testUpdateUser()
    {

    }

    public function testAddUser()
    {

    }



    public function testAddMessage()
    {

    }

    public function testSetSessionVar()
    {

    }

    public function testSafeFetchRow()
    {

    }

    public function testCountRows()
    {

    }

    public function testTogglePrivilege()
    {

    }

    public function testGetMessageMetaData()
    {

    }


}
