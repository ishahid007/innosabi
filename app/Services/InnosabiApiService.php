<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ApiService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class InnosabiApiService implements ApiService
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $username,
        private readonly string $password
    ) {}

    /**
     * Fetch data from Innosabi API.
     *
     * @param  string  $endpoint  The API endpoint (e.g., "/suggestion")
     * @param  array  $query  Query parameters (e.g., ["filter" => "active"])
     */
    public function get(string $endpoint, array $query = []): Response
    {
        return Http::withBasicAuth($this->username, $this->password)
            ->get($this->baseUrl.$endpoint, $query);
    }
}
