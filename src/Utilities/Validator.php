<?php

namespace Deputy\Utilities;

use Deputy\Exceptions\ValidationException;

/**
 * Class Validator
 * @package Deputy\Utilities
 */
class Validator
{
    /**
     * Validate required fields
     * @param $data
     * @param array $requiredFields
     * @param bool $multiLevel
     * @throws ValidationException
     */
    public static function required($data, array $requiredFields, $multiLevel = false)
    {
        if (empty($data)) {
            throw new ValidationException("Missing parameters.");
        }

        if ($multiLevel) {
            foreach ($data as $record) {
                static::required($record, $requiredFields, false);
            }
        } else {
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new ValidationException("Parameter $field is required field.");
                }
            }
        }

    }
}