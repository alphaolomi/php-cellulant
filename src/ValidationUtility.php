<?php

namespace Alphaolomi\Cellulant;

use DateTime;
use RuntimeException;

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
    protected $validatedData = [];
    protected $rules = [];
    protected $errors = [];
    protected $messages = [];

    public function validate(array $data, array $rules, $messages = [])
    {
        $this->rules = $rules;
        $this->errors = [];
        $this->messages = $messages;

        foreach ($rules as $field => $rule) {
            $this->validateField($field, $data[$field] ?? null, $rule);
        }

        return $this->getValidationResult();
    }

    public function getValidatedData()
    {
        return $this->validatedData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getValidationResult()
    {
        return $this->getValidatedData();
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

            // Convert rule to camelCase recursively
            $methodName = 'validate' . implode('', array_map('ucfirst', explode('_', $rule)));

            if (method_exists($this, $methodName)) {
                $isValid = $this->$methodName($field, $value, $params);

                if ($isValid && !isset($this->validatedData[$field])) {
                    $this->addValidated($field, $value);
                }

                if (!$isValid) {
                    $messageKey = $field . '.' . $rule;
                    $message = $this->messages[$messageKey] ?? "Invalid value for '$field'";
                    $this->addError($field, $message);
                }
            } else {
                throw new RuntimeException("Validation rule '$rule' does not exist");
            }
        }
    }

    protected function addValidated($field, $value)
    {
        $this->validatedData[$field] = $value;
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

    protected function validateEmail($field, $value, $params)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected function validateDate($field, $value, $params)
    {
        $format = 'Y-m-d';
        if (count($params) !== 0) {
            $format = $params[0];
        }
        $date = DateTime::createFromFormat($format, $value);

        return $date && $date->format($format) === $value;
    }

    protected function validateDateTime($field, $value, $params)
    {
        $format = 'Y-m-d H:i:s';
        if (count($params) !== 0) {
            $format = $params[0];
        }
        $date = DateTime::createFromFormat($format, $value);

        return $date && $date->format($format) === $value;
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

    protected function validateDateTimeAfter($field, $value, $params)
    {
        $format = 'Y-m-d H:i:s';
        $afterDateField = $params[0];

        if (isset($this->rules[$afterDateField]) && isset($this->rules[$afterDateField]['value'])) {
            $afterDate = $this->rules[$afterDateField]['value'];

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
        $pattern = '/^[0-9]{10}$/';

        if (!empty($params) && isset($params[0])) {
            $pattern = $params[0];
        }

        return preg_match($pattern, $value) === 1;
    }
}
