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
use \VerticalBarChart;


class MessageAnalyticsModelTest extends TestCase
{

    protected $config;
    protected $test_db_config;
    protected $libchartPath;

    protected function setUp(): void
    {
        $this->config = require("../app/settings.php");
        $this->test_db_config = (TEST_DB_SETTINGS['pdo_test_settings']);
        $this->libchartPath = (LIB_CHART_CLASS_PATH);


    }
    public function test__construct()
    {
        $testAnalytics = new MessageAnalyticsModel();

        $this->assertInstanceOf(MessageAnalyticsModel::class,$TestAnalytics);
        $this->assertEmpty($testAnalytics->getVars()[0]);
        $this->assertEmpty($testAnalytics->getVars()[1]);
        $this->assertEmpty($testAnalytics->getVars()[2]);
    }

    public function testSetStoredMessageData()
    {
        $testAnalytics = new MessageAnalyticsModel();
        $testMessageWrapper = new DatabaseWrapper();
        $testMessageWrapper->setDatabaseConnectionSettings($this->test_db_config);
        $testMessages = $testMessageWrapper->getMessages();

        $this->assertNull($testAnalytics->setStoredMessageData($testMessages));


    }

    public function testCreateBarChart()
    {
        $testAnalytics = new MessageAnalyticsModel();


        $testMessageWrapper = new DatabaseWrapper();
        $testMessageWrapper->setDatabaseConnectionSettings($this->test_db_config);
        $testMessages = $testMessageWrapper->getMessages();
        $testAnalytics->setStoredMessageData($testMessages);

        $testAnalytics->createBarChart();

        $this->assertDirectoryExists("/p3t/phpappfolder/public_php/CTEC3110-Coursework/media/charts/");
        $this->assertFileExists("../p3t/phpappfolder/public_php/CTEC3110-Coursework/media/charts/message-barchart.png");
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
