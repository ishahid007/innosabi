<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ApiService;
//
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class InnosabiApiService implements ApiService
{
    private string $apiUrl;

    private string $apiUsername;

    private string $apiPassword;

    public function __construct()
    {
        //
        $this->apiUrl = env('INNOSABI_API_URL');
        $this->apiUsername = env('INNOSABI_API_USERNAME');
        $this->apiPassword = env('INNOSABI_API_PASSWORD');
        //
        if (! $this->apiUrl || ! $this->apiUsername || ! $this->apiPassword) {
            Log::error('Innosabi API credentials are missing');
            throw new \Exception('Innosabi API credentials are missing');
        }
    }

    /**
     * Fetch data from Innosabi API.
     * only use include, filter, order, limit and page
     */
    public function fetch(array $query): array
    {
        //
        try {
            $endpoint = '/suggestion';
            $response = Http::withBasicAuth($this->apiUsername, $this->apiPassword)
                ->get($this->apiUrl.$endpoint, $query);

            if ($response->failed()) {
                Log::error('Innosabi API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Innosabi API request failed');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Innosabi API request failed', [
                'message' => $e->getMessage(),
            ]);
            throw new \Exception('Innosabi API request failed');
        }
    }
}
