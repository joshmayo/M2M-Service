<?php
/**
 * MessageAnalyticsModel.php
 *
 */

namespace M2MConnect;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MessageAnalyticsModel
{
    private $output_chart_details;
    private $stored_message_data;
    private $output_chart_path_and_name;

    public function __construct()
    {
        $this->stored_message_data = [];
        $this->output_chart_details = '';
        $this->output_chart_path_and_name = '';

        $this->log = new Logger('logger');
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'analytics.log',Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'analytics_error.log',Logger::ERROR));
    }

    public function __destruct() {}

    public function setStoredMessageData(array $stored_message_data)
    {
        $this->stored_message_data = $stored_message_data;
    }

    public function createLineChart()
    {
        $this->createChartDetails();
        $this->makeLineChart();
    }

    public function getLineChartDetails()
    {
        return $this->output_chart_details;
    }

    private function createChartDetails()
    {
        $output_chart_name = 'message-linechart.png';

        $output_chart_location = LIB_CHART_OUTPUT_PATH;
        $this->output_chart_details = LANDING_PAGE . DIRSEP . $output_chart_location . $output_chart_name;
        $this->output_chart_path_and_name = LIB_CHART_FILE_PATH . $output_chart_location . $output_chart_name;


        if (!is_dir($output_chart_location))
        {
            mkdir($output_chart_location, 0755, true);
        }
    }

    private function makeLineChart()
    {
        $this->log->info('Attempting to create chart.');

        $series_data = $this->stored_message_data;

        $this->log->info('Stored message data: ' . explode($series_data));

        $this->log->info(explode($this->stored_message_data));

        $chart = new \LineChart();

        $chart->getPlot()->getPalette()->setLineColor(array(new \Color(255, 130, 0), new \Color(255, 255, 255)));
        $series1 = new \XYDataSet();
        foreach ($series_data as $data_row)
        {
            $index = $data_row['date'];
            $datum = $data_row['temperature'];
            $series1->addPoint(new \Point($index, $datum));
        }

        $dataSet = new \XYSeriesDataSet();

        $chart->setDataSet($dataSet);

        $chart->setTitle('Temperature Over Time');
        $chart->getPlot()->setGraphCaptionRatio(0.75);

        $chart->render($this->output_chart_path_and_name);
    }
}