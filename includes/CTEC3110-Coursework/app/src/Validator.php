<?php

namespace Country;

class Validator
{
    public function __construct() { }

    public function __destruct() { }

    public function validateCountryCode($country_code_to_check)
    {
        $checked_country = false;
        if (isset($country_code_to_check))
        {
            if (!empty($country_code_to_check))
            {
                if (strlen($country_code_to_check) == 2)
                {
                    $checked_country = $country_code_to_check;
                }
            }
            else
            {
                $checked_country = 'none selected';
            }
        }
        return $checked_country;
    }

    public function validateDetailType($type_to_check)
    {
        $checked_detail_type = false;
        $detail_types = DETAIL_TYPES;

        if (in_array($type_to_check, $detail_types) === true)
        {
            $checked_detail_type = $type_to_check;
        }

        return $checked_detail_type;
    }

    public function validateDownloadedData($tainted_data)
    {
        $validated_string_data = '';

        $validated_string_data = filter_var($tainted_data, FILTER_SANITIZE_STRING);

        return $validated_string_data;
    }
}