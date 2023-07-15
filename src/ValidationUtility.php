<?php

namespace Alphaolomi\Cellulant;

use DateTime;

/**
 * A simple validation utility class
 *
 * @author Alpha Olomi <alphaolomi@gmail.com>
 * @since 1.0.0
 * @api
 *
 * @package Alphaolomi\Cellulant
 */
class ValidationUtility
{
    protected $rules = [];
    protected $errors = [];

    public function validate(array $data, array $rules)
    {
        $this->rules = $rules;
        $this->errors = [];

        foreach ($rules as $field => $rule) {
            $this->validateField($field, $data[$field] ?? null, $rule);
        }

        return $this->errors;
    }

    protected function validateField($field, $value, $rule)
    {
        $rules = explode('|', $rule);

        foreach ($rules as $rule) {
            $params = [];

            if (strpos($rule, ':') !== false) {
                [$rule, $params] = explode(':', $rule, 2);
                $params = explode(',', $params);
            }

            $methodName = 'validate' . ucfirst($rule);

            if (method_exists($this, $methodName)) {
                $isValid = $this->$methodName($field, $value, $params);

                if (!$isValid) {
                    $this->addError($field, $rule);
                }
            }
        }
    }

    protected function addError($field, $rule)
    {
        $this->errors[$field][] = $rule;
    }

    protected function validateRequired($field, $value, $params)
    {
        return isset($value) && $value !== '';
    }

    protected function validateNullable($field, $value, $params)
    {
        return true; // Allow any value, including null
    }

    protected function validateString($field, $value, $params)
    {
        return is_string($value);
    }

    protected function validateDate($field, $value, $params)
    {
        $format = 'Y-m-d';
        if (count($params) !== 0) {
            $format = $params[0];
        }
        $date = DateTime::createFromFormat($format, $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    protected function validateAfter($field, $value, $params)
    {
        if (count($params) === 0) {
            return false;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        $afterDate = DateTime::createFromFormat('Y-m-d', $params[0]);

        return $date && $afterDate && $date > $afterDate;
    }

    protected function validateDateTimeAfter($field, $value, $params, $data)
    {
        $format = 'Y-m-d H:i:s';
        $afterDateField = $params[0];

        if (isset($data[$afterDateField])) {
            $afterDate = $data[$afterDateField];

            $dateTime = DateTime::createFromFormat($format, $value);
            $afterDateTime = DateTime::createFromFormat($format, $afterDate);

            if ($dateTime && $dateTime->format($format) === $value && $afterDateTime && $afterDateTime->format($format) === $afterDate) {
                return $dateTime > $afterDateTime;
            }
        }

        return false;
    }

    protected function validateUrl($field, $value, $params)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    protected function validatePhone($field, $value, $params)
    {
        // Add your own phone validation logic here
        return true;
    }
}
