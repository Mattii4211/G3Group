<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class FormData
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $clientNumber,
        public int $choose,
        public int $agreement1,
        public int $agreement2,
        public ?int $agreement3,
        public ?int $phoneNumber,
        public ?string $accountNumber,
    ) {}
}