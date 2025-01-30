<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//
use App\Http\Requests\SuggestionRequest;
use App\Http\Resources\SuggestionResource;
use App\Services\InnosabiApiService;
//
use Illuminate\Support\Facades\Cache;

class SuggestionController extends Controller
{
    private int $cacheDuration = 60;

    /**
     *Can be extended for multiple API services
     *
     * @return void
     */
    public function __construct(private InnosabiApiService $apiService) {}

    /**
     * Handle the incoming request.
     * Automatically validates the incoming request
     * Returns the suggestion from the API service via collection
     * Caches the response for 1 minute
     * Returns the response as a resource
     * Only cache if the request is the same
     */
    public function __invoke(SuggestionRequest $request): SuggestionResource
    {
        // Cache key based on the request
        $cacheKey = md5(json_encode($request->validated()));
        //
        $suggestion = Cache::remember($cacheKey, $this->cacheDuration, function () use ($request) {
            $response = $this->apiService->fetch($request->validated());

            // Return the response as a resource
            return new SuggestionResource($response);
        });

        return $suggestion;
    }
}
