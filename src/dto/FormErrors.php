<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class FormErrors
{
    public function __construct(
        public string $field,
        public string $message
    ) {}
}