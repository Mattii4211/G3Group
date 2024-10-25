<?php

declare(strict_types=1);

namespace App\Validator;

final class OrderTableValidator
{
    private const AVAIBLE_FIELD_TO_SORT = ['name', 'surname', 'email'];

    public static function validate(string $orderBy): bool
    {
        return in_array($orderBy, self::AVAIBLE_FIELD_TO_SORT);
    }
}