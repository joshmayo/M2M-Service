<?php

namespace M2MConnect;

class Validator
{
    public function __construct() { }

    public function __destruct() { }

    public function validateHeaterCode($heater_code_to_check)
    {
        $heater_code_to_check = (int)$heater_code_to_check;
        $checked_heater_code = false;
        if (isset($heater_code_to_check))
        {
            if (is_int($heater_code_to_check) && $heater_code_to_check <= 99 && $heater_code_to_check >= 0)
            {
                $checked_heater_code = $heater_code_to_check;
            }
            else {
                $checked_heater_code = 'invalid number';
            }
        }
        return $checked_heater_code;
    }

    public function validateKeypadCode($keypad_to_check)
    {
        $keypad_to_check = (int)$keypad_to_check;
        $checked_keypad_code = false;
        if (isset($keypad_to_check))
        {
            if (is_int($keypad_to_check) && $keypad_to_check <= 9 && $keypad_to_check >= 0)
            {
                $checked_keypad_code = $keypad_to_check;
            }
            else {
                $checked_keypad_code = 'invalid number';
            }
        }
        return $checked_keypad_code;
    }

    public function validateSwitch($switch_to_check)
    {
        $checked_switch = false;

        if (is_bool($switch_to_check))
        {
            $checked_switch = $switch_to_check;
        }
        elseif ($switch_to_check = 'on')
        {
            $checked_switch = true;
        }
        else {
            $checked_switch = 'invalid switch';
        }

        return $checked_switch;
    }

    public function validateDownloadedData($tainted_data)
    {
        $validated_string_data = '';

        $validated_string_data = filter_var($tainted_data, FILTER_SANITIZE_STRING);

        return $validated_string_data;
    }
}