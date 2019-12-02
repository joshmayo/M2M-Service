<?php
/**
 * class Message
 *
 */

namespace M2MConnect;


class Message
{
    private $source_msisdn;
    private $destination_msisdn;
    private $switch_1;
    private $switch_2;
    private $switch_3;
    private $switch_4;
    private $fan;
    private $heater;
    private $keypad;
    private $received_time;

    public function __construct()
    {
        $this->source_msisdn = '';
        $this->destination_msisdn = '';
        $this->switch_1 = '';
        $this->switch_2 = '';
        $this->switch_3 = '';
        $this->switch_4 = '';
        $this->fan = '';
        $this->heater = '';
        $this->keypad = '';
        $this->received_time = '';
    }

    public function __destruct()
    {
    }

    public function getSourceMsisdn()
    {
        return $this->source_msisdn;
    }

    public function getDestinationMsisn()
    {
        return $this->destination_msisdn;
    }

    public function getSwitch1()
    {
        return $this->switch_1;
    }

    public function getSwitch2()
    {
        return $this->switch_2;
    }

    public function getSwitch3()
    {
        return $this->switch_3;
    }

    public function getSwitch4()
    {
        return $this->switch_4;
    }

    public function getFan()
    {
        return $this->fan;
    }

    public function getHeater()
    {
        return $this->heater;
    }

    public function getKeypad()
    {
        return $this->keypad;
    }

    public function getReceivedTime()
    {
        return $this->received_time;
    }

}