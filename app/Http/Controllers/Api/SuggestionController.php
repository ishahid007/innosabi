<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//
use App\Http\Requests\SuggestionRequest;
use App\Http\Resources\SuggestionResource;
use App\Services\InnosabiApiService;

class SuggestionController extends Controller
{
    /**
     *Can be extended for multiple API services
     *
     * @return void
     */
    public function __construct(private InnosabiApiService $apiService) {}

    /**
     * Handle the incoming request.
     * Automatically validates the incoming request
     * Returns the response from the API service via collection
     */
    public function __invoke(SuggestionRequest $request): SuggestionResource
    {
        //
        $response = $this->apiService->fetch($request->validated());

        return new SuggestionResource($response);
    }
}
