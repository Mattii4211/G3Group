<?php

declare(strict_types=1);

namespace App\Service\Form;
use App\DTO\FormData;

interface FormInterface 
{
    public function saveForm(FormData $formData): bool;
    public function counter(): string;
}