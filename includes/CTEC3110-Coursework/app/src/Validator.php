<?php
/**
 * Validator.php
 *
 * Message Validation check class
 *
 * All Validations have a default failsafe boolean value as false. If for a reason validation does not occur,
 * a false value with be supplied as a security feature.
 *
 * @author Joshua Mayo, Sophie Hughes, Kieran McCrory
 *
 */


namespace M2MConnect;

class Validator
{
    public function __construct() { }

    public function __destruct() { }

    /**
     * Function for validating the heater setting field.
     *
     * Validates the heater setting, ensuring that the parameter is numeric and within accepted values
     *
     * @param $heater_code_to_check
     *
     * @return bool|int|string - Returns false if value is unset. Returns the setting as int type if
     * validated successfully. Returns a string if validation fails if the parameter is set.
     */
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

    /**
     * Function for validating the keypad  field.
     *
     * Validates the keypad button setting, ensuring that the  parameter is
     * numeric and within accepted values.
     *
     * @param $keypad_to_check
     *
     * @return bool|int|string - Returns false if value is unset. Returns the setting as int type if
     * validated successfully. Returns a string if validation fails if the parameter is set.
     */

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

    /**
     * Function for validating a switch  field.
     *
     * Validates the inputted switch setting, ensuring that the  parameter is boolean or a string value of 'on'.
     *
     * @param $switch_to_check
     *
     * @return bool - Returns the boolean value of the switch.
     */

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


    /**
     * Function for validating the MSISDN  field.
     *
     * Validates the inputted MSISDN parameter, ensuring that the parameter is
     * numeric and within accepted values.
     *
     * @param $tainted_param
     *
     * @return bool|string - Returns false if value is unset or illegal. Returns the MSISDN value if it meets the rules.
     */

    public function validateMSISDN($tainted_param)
    {
        $checked_MSISDN = false;

        if(is_numeric($tainted_param) && strlen($tainted_param) == 12)
        {
            $checked_MSISDN = $tainted_param;
        }

        return $checked_MSISDN;
    }

    /**
     * Function for validating the Received Time Field.
     *
     * Ensures the field meets the length and format required. Removes any malicious input.
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field is empty or illegal. Returns the time field if correct as
     * as an array.
     */

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

    /**
     * Function for validating the Bearer field.
     *
     * Ensures the bearer value is the required length of fields and not illegal. Removes any malicious input.
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field is empty or illegal. Returns the value with sanitised characters
     * if validation is successful.
     */

    public function validateBearer($tainted_param)
    {
        $checked_Bearer = false;

        if (!empty($tainted_param) && $tainted_param === "SMS" || $tainted_param === "GPRS" || $tainted_param === "BEEP")
        {
            $checked_Bearer = filter_var($tainted_param, FILTER_SANITIZE_STRING);
        }
        return $checked_Bearer;
    }

    /**
     * Function for validating the Message Reference field.
     *
     * Ensures the Reference value is numerical and is within specified parameters.
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field is empty or illegal. Returns the field if validation successful.
     */

    public function validateMessageRef($tainted_param)
    {
        $checked_ref = false;

        if (is_numeric($tainted_param) && strlen($tainted_param) <= 5)
        {
            if(strpos($tainted_param, '.') === false)
            {
                if($tainted_param >= 0 && $tainted_param <= 65535)
                {
                    $checked_ref = $tainted_param;
                }
            }

        }
        return $checked_ref;
    }

    /**
     * Function for validating the Message Reference field.
     *
     * Ensures the Reference value is not empty and sanitises the string of any unwanted tags
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field is empty. Returns the value with sanitised characters
     * if validation is successful.
     */

    public function validateMessage($tainted_param)
    {
        $checked_Message = false;

        if (!empty($tainted_param))
        {
            $checked_Message = filter_var($tainted_param, FILTER_SANITIZE_STRING);
        }
        return $checked_Message;
    }

    /**
     * Function for validating username.
     *
     * Ensures the Reference value matches the given regex and sanitises the string of any unwanted tags
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field does not match regex. Returns the value of sanitised string
     * if validation is successful.
     */

    public function validateUsername($tainted_param)
    {
        $checked_Username = false;

        $usernameRegex = '/[A-Za-z0-9]{3,19}$/';

        if(preg_match($usernameRegex, $tainted_param))
        {
            $checked_Username = filter_var($tainted_param, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $checked_Username;
    }

    /**
     * Function for validating password.
     *
     * Ensures the Reference value matches the given regex and sanitises the string of any unwanted tags
     *
     * @param $tainted_param
     *
     * @return bool|mixed - Returns false if the field does not match regex. Returns the value of sanitised string
     * if validation is successful.
     */

    public function validatePassword($tainted_param)
    {
        $checked_Password = false;

        $passwordRegex = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{7,39}$/';

        if(preg_match($passwordRegex, $tainted_param))
        {
            $checked_Password = filter_var($tainted_param, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $checked_Password;
    }
}