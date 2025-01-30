<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Contracts\ApiService;
use App\Http\Controllers\Controller;
//
use App\Http\Requests\SuggestionRequest;
use App\Http\Resources\SuggestionResource;
use Illuminate\Http\JsonResponse;
//
use Illuminate\Support\Facades\Cache;

//

class SuggestionController extends Controller
{
    const int CACHE_DURATION = 60;

    /**
     *Can be extended for multiple API services
     *
     * @return void
     */
    public function __construct(private ApiService $innosabiApiService)
    {
        // Resolve services explicitly
        $this->innosabiApiService = app(abstract: 'innosabiApi');
    }

    /**
     * Handle the incoming request.
     * Automatically validates the incoming request
     * Caches the response for 1 minute
     * Returns the response as a resource
     * Only cache if the request is the same
     */
    public function __invoke(SuggestionRequest $request): SuggestionResource|JsonResponse
    {
        // Cache key
        $cacheKey = 'suggestion_'.md5(json_encode($request->validated()));
        $endpoint = '/suggestion';
        try {
            $suggestion = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($request, $endpoint): SuggestionResource {
                $response = $this->innosabiApiService->get($endpoint, $request->validated());

                //
                return new SuggestionResource($response->json());
            });

            //
            return $suggestion;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
