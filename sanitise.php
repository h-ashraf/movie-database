<?php
// function to clean any user data
function sanitise($str, $connection)
{
    // escapes dangerous characters
    $str = mysqli_real_escape_string($connection, $str);
    // ensure any html code is safe by changing the reserved characters to entitites
    $str = htmlentities($str);
    // return the final string
    return $str;
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateString($field, $minlength, $maxlength)
{
    if (strlen($field) < $minlength) {
        // wasn't a valid length, return a help message:
        return "Minimum length: " . $minlength;
    } elseif (strlen($field) > $maxlength) {
        // wasn't a valid length, return a help message:
        return "Maximum length: " . $maxlength;
    }
    // data was valid, return an empty string:
    return "";
}

function validateEmail($field)
{
    // checks if email format is valid
    if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return "invalid email";
    }
    // data was valid, return an empty string:
    return "";
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateInt($field, $min, $max)
{
    $options = [
        "options" => [
            "min_range" => $min,
            "max_range" => $max,
        ],
    ];

    if (!filter_var($field, FILTER_VALIDATE_INT, $options)) {
        // wasn't a valid integer, return a help message:
        return "Not a valid number (must be whole and in the range: " .
            $min .
            " to " .
            $max .
            ")";
    }
    // data was valid, return an empty string:
    return "";
}

?>
