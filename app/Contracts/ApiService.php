<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\Client\Response;

interface ApiService
{
    public function get(string $endpoint, array $query): Response;
}
