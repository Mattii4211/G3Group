<?php

declare(strict_types=1);

namespace App\Validator;
use App\DTO\FormErrors;
use Exception;

final class FormValidator
{
    private const MAX_NAME_LENGTH = 30;
    private const MAX_SURNAME_LENGTH = 40;
    private const MAX_EMAIL_LENGTH = 80;
    private const CLIENT_NUMBER_REGEX = '/\A000[0-9]{3}-[A-Z]{5}\z/';
    private const POLISH_IBAN_REGEX = '/\APL[0-9]{26}\z/';
    private const TRUE_FALSE_VALUES = [0,1];
    private const CHOOSE_CORRECT_VALUES = [1,2];
    private const PHONE_NUMBER_REGEX = '/\A[0-9]{9}\z/';

    public static function validate(array $postData): true|array
    {
        $formValidator = new FormValidator();
        $errors = [];
        foreach ($postData as $field => $value) {
            try {
                $function = 'validate' . ucfirst($field);
                $formValidator->$function($value);
            } catch (Exception $e) {
                array_push($errors, new FormErrors($field, $e->getMessage()));
            }
        }

        return count($errors) ? $errors : true;
    }

    private function validateName(string $name): bool
    {
        $maxLength = self::MAX_NAME_LENGTH;
        $pattern = "/\A[A-Za-z]{2,$maxLength}\z/";
        if (!(strlen($name) <= $maxLength && preg_match($pattern, $name))) {
            throw new Exception(message: 'Incorrect value! ' . $name);
        }
        return true;
    }

    private function validateSurname(string $surname): bool
    {
        $maxLength = self::MAX_SURNAME_LENGTH;
        $pattern = "/\A[A-Za-z]{2,$maxLength}\z/";
        if (!(strlen($surname) <= $maxLength && preg_match($pattern, $surname))) {
            throw new Exception(message: 'Incorrect value! ' . $surname);
        }

        return true;
    }

    private function validateEmail(string $email): bool
    {
        if (!(strlen($email) <= self::MAX_EMAIL_LENGTH && filter_var($email, FILTER_VALIDATE_EMAIL))) {
            throw new Exception(message: 'Incorrect value! ' . $email);
        }

        return true;
    }

    private function validateClientNumber(string $clientNumber): bool
    {
        if (!preg_match(self::CLIENT_NUMBER_REGEX, $clientNumber)) {
            throw new Exception(message: 'Incorrect value! ' . $clientNumber);
        }

        return true;
    }
    
    private function validateAccount(string $accountNumber): bool
    {
        if (!preg_match(self::POLISH_IBAN_REGEX, $accountNumber)) {
            throw new Exception(message: 'Incorrect value! ' . $accountNumber);
        }

        return true;
    }

    private function validateChoose(int $choose): bool
    {
        if (!in_array($choose, self::CHOOSE_CORRECT_VALUES)) {
            throw new Exception(message: 'Incorrect value! ' . $choose);
        }
        return true;
    }

    private function validateAgreement1(int $agreement1): bool
    {
        if (!in_array($agreement1, self::TRUE_FALSE_VALUES)) {
            throw new Exception(message: 'Incorrect value! ' . $agreement1);
        }
        return true;
    }
    private function validateAgreement2(int $agreement2): bool
    {
        if (!in_array( $agreement2, self::TRUE_FALSE_VALUES)) {
            throw new Exception(message: 'Incorrect value! ' . $agreement2);
        }
        return true;
    }

    private function validateAgreement3(int $agreement3): bool
    {
        if (!in_array( $agreement3, self::TRUE_FALSE_VALUES)) {
            throw new Exception(message: 'Incorrect value! ' . $agreement3);
        }
        return true;
    }

    private function validatePhone(string $phone): bool
    {
        if (!preg_match(self::PHONE_NUMBER_REGEX, $phone)) {
            throw new Exception(message: 'Incorrect value! ' . $phone);
        }

        return true;
    }
}