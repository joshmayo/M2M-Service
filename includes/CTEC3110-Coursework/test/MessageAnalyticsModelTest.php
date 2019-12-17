<?php
/**
* MessageTest.php
*
 * Unit Tests for MessageAnalyticsModel class
 *
 * @uses \M2MConnect\MessageAnalyticsModel
*
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
*/

namespace M2MConnect;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;


class MessageAnalyticsModelTest extends TestCase
{

    protected $config;

    protected function setUp(): void
    {
        $this->config = require("../app/settings.php");

    }
    public function test__construct()
    {
        $TestAnalytics = new MessageAnalyticsModel();

        $this->assertInstanceOf(MessageAnalyticsModel::class,$TestAnalytics);
        $this->assertEmpty($TestAnalytics->getVars()[0]);
        $this->assertEmpty($TestAnalytics->getVars()[1]);
        $this->assertEmpty($TestAnalytics->getVars()[2]);
    }

    public function testSetStoredMessageData()
    {
        $TestAnalytics = new MessageAnalyticsModel();

        $this->assertSame([23,21],$TestAnalytics->setStoredMessageData([23,21]));


    }
    public function testCreateBarChart()
    {

    }

    public function testCreatePieChart()
    {

    }

    public function testCreateLineChart()
    {

    }

    public function testGetPieChartDetails()
    {

    }





    public function testGetLineChartDetails()
    {

    }
}
