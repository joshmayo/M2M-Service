<?php
/**
 * class Message
 *
 * An object to represent the messages that are sent and retrieved by the application.
 *
 * Object is constructed with a set of default values.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
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

    public function __construct(
        $source_msisdn = "",
        $destination_msisdn = "",
        $switch_1 = null,
        $switch_2 = null,
        $switch_3 = null,
        $switch_4 = null,
        $fan = null,
        $heater = 0,
        $keypad = 0,
        $received_time = null
    ) {
        $this->source_msisdn = $source_msisdn;
        $this->destination_msisdn = $destination_msisdn;
        $this->switch_1 = $switch_1;
        $this->switch_2 = $switch_2;
        $this->switch_3 = $switch_3;
        $this->switch_4 = $switch_4;
        $this->fan = $fan;
        $this->heater = $heater;
        $this->keypad = $keypad;
        $this->received_time = $received_time;
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