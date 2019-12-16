<?php

namespace M2MConnect;

class Validator
{
    public function __construct() { }

    public function __destruct() { }

    public function validateHeaterCode($heater_code_to_check)
    {
        $checked_heater_code = false;
        if (isset($heater_code_to_check))
        {
            if (is_numeric($heater_code_to_check) && $heater_code_to_check <= 99 && $heater_code_to_check >= 0)
            {
                $checked_heater_code = (int)$heater_code_to_check;
            }
            else {
                $checked_heater_code = 'invalid number';
            }
        }
        return $checked_heater_code;
    }

    public function validateKeypadCode($keypad_to_check)
    {
        $checked_keypad_code = false;
        if (isset($keypad_to_check))
        {
            if (is_numeric($keypad_to_check) && $keypad_to_check <= 9 && $keypad_to_check >= 0)
            {
                $checked_keypad_code = (int)$keypad_to_check;
            }
            else {
                $checked_keypad_code = 'invalid number';
            }
        }
        return $checked_keypad_code;
    }

    public function validateSwitch($switch_to_check)
    {
        if (is_bool($switch_to_check))
        {
            $checked_switch = $switch_to_check;
        }
        elseif ($switch_to_check == 'on')
        {
            $checked_switch = true;
        }
        else {
            $checked_switch = 'invalid switch';
        }

        return $checked_switch;
    }

    public function validateMSISDN($tainted_param)
    {
        $checked_MSISDN = false;

        if(is_numeric($tainted_param) && strlen($tainted_param) == 12)
        {
            $checked_MSISDN = $tainted_param;
        }

        return $checked_MSISDN;
    }

    public function validateReceivedTime($tainted_param)
    {
        $checked_time = false;

        $dateTimeRegex = '/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2}):(\d{2})/';

        if (!empty($tainted_param) && preg_match($dateTimeRegex, $tainted_param))
        {
            $checked_time = filter_var($tainted_param, FILTER_SANITIZE_STRING);
        }
        return $checked_time;
    }

    public function validateBearer($tainted_param)
    {
        $checked_Bearer = false;

        if (!empty($tainted_param) && strlen($tainted_param) >= 3 && strlen($tainted_param) <= 5)
        {
            $checked_Bearer = filter_var($tainted_param, FILTER_SANITIZE_STRING);
        }
        return $checked_Bearer;
    }

    public function validateMessageRef($tainted_param)
    {
        $checked_ref = false;

        if (is_numeric($tainted_param))
        {
            $checked_ref = $tainted_param;
        }
        return $checked_ref;
    }

    public function validateMessage($tainted_param)
    {
        $checked_Message = false;

        if (!empty($tainted_param))
        {
            $checked_Message = filter_var($tainted_param, FILTER_SANITIZE_STRING);
        }
        return $checked_Message;
    }

}