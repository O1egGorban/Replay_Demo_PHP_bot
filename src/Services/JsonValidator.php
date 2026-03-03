<?php 
namespace Bot\Services;


class JsonValidator {

    public static function validate(string $jsonString) : bool{
        json_decode($jsonString);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return true;
    }
}

?>