<?php

declare(strict_types=1);

namespace App\Contracts;

interface ApiService
{
    public function fetch(array $params): array;
}
