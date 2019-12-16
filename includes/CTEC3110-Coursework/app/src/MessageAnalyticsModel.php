<?php
/**
 * MessageAnalyticsModel.php
 *
 * Processes and handles message analytics output.

 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
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
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'analytics.log', Logger::INFO));
        $this->log->pushHandler(new StreamHandler(LOGS_PATH . 'analytics_error.log', Logger::ERROR));
    }

    public function __destruct()
    {
    }

    public function setStoredMessageData(array $stored_message_data)
    {
        $this->stored_message_data = $stored_message_data;
    }

    public function createLineChart()
    {
        $this->createChartDetails('message-linechart.png');
        $this->makeLineChart();
    }

    public function createPieChart()
    {
        $this->createChartDetails('message-piechart.png');
        $this->makePieChart();
    }

    public function createBarChart()
    {
        $this->createChartDetails('message-barchart.png');
        $this->makeBarChart();
    }

    public function getLineChartDetails()
    {
        return $this->output_chart_details;
    }

    public function getPieChartDetails()
    {
        return $this->output_chart_details;
    }

    /**
     * Creates chart details object for chart creation.
     *
     * Will also create a chart output directory if it does not exist.
     *
     * @param $chart_name
     *
     */

    private function createChartDetails($chart_name)
    {
        $output_chart_location = LIB_CHART_OUTPUT_PATH;
        $this->output_chart_details = $output_chart_location . $chart_name;
        $this->output_chart_path_and_name = $output_chart_location . $chart_name;


        if (!is_dir($output_chart_location)) {
            mkdir($output_chart_location, 0755, true);
        }
    }

    /**
     * Creates and populates a line chart
     *
     * Will render the completed chart to the output path with specified name.
     *
     * @uses LineChart
     *
     * Stored_message_data is the object where the information is parsed to the makeLineChart function.
     *
     */

    private function makeLineChart()
    {
        $this->log->info('Attempting to create line chart.');

        $series_data = $this->stored_message_data;

        $chart = new \LineChart(1400, 700);

        $chart->getPlot()->getPalette()->setLineColor(array(
            new \Color(240, 53, 160),
            new \Color(178, 69, 240)));

        $series1 = new \XYDataSet();
        foreach ($series_data as $data_row) {
            $index = $data_row['received_time'];
            $datum = $data_row['heater'];
            $this->log->info('Attempting add point to line chart: ' . $index . ' ' . $datum);
            $series1->addPoint(new \Point($index, $datum));
        }

        $chart->setDataSet($series1);

        $chart->setTitle('');
        $chart->getPlot()->setGraphCaptionRatio(0.75);

        $chart->render($this->output_chart_path_and_name);
    }

    /**
     * Creates and populates a pie chart
     *
     * Will render the completed chart to the output path with specified name.
     *
     * @uses PieChart
     *
     * Stored_message_data is the object where the information is parsed to the makePieChart function.
     *
     */

    private function makePieChart()
    {
        $this->log->info('Attempting to create pie chart.');

        $series_data = $this->stored_message_data;

        $chart = new \PieChart(1400, 600);

        $chart->getPlot()->getPalette()->setPieColor(array(
            new \Color(239, 52, 160),
            new \Color(239, 56, 62),
            new \Color(239, 151, 59),
            new \Color(230, 239, 62),
            new \Color(137, 239, 66),
            new \Color(69, 239, 92),
            new \Color(72, 239, 185),
            new \Color(76, 204, 239),
            new \Color(79, 118, 239),
            new \Color(130, 82, 240)
        ));

        $series1 = new \XYDataSet();
        $keypad_inputs = [];

        foreach ($series_data as $data_row) {
            array_push($keypad_inputs, $data_row['keypad']);
        }

        foreach(array_count_values($keypad_inputs) as $keypad => $keypad_value)
        {
            $this->log->info('Attempting add point to pie chart: ' . $keypad . ' ' . $keypad_value);
            $series1->addPoint(new \Point($keypad, $keypad_value));
        }

        $chart->setDataSet($series1);

        $chart->setTitle('');
        $chart->getPlot()->setGraphCaptionRatio(0.75);

        $chart->render($this->output_chart_path_and_name);
    }

    private function makeBarChart()
    {
        $this->log->info('Attempting to create bar chart.');

        $series_data = $this->stored_message_data;

        $chart = new \VerticalBarChart(1400, 700);

        $series1 = new \XYDataSet();
        $fan_inputs = [];

        $chart->getPlot()->getPalette()->setBarColor(array(
            new \Color(240, 53, 160),
            new \Color(178, 69, 240)));

        foreach ($series_data as $data_row) {
            array_push($fan_inputs, $data_row['fan']);
        }

        foreach(array_count_values($fan_inputs) as $fan => $fan_value)
        {
            $fan = $fan == '0' ? 'Backwards' : 'Forwards';
            $this->log->info('Attempting add point to bar chart: ' . $fan . ' ' . $fan_value);
            $series1->addPoint(new \Point($fan, $fan_value));
        }

        $chart->setDataSet($series1);

        $chart->setTitle('');
        $chart->getPlot()->setGraphCaptionRatio(0.75);

        $chart->render($this->output_chart_path_and_name);
    }
}