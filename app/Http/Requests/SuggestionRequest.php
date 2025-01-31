<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SuggestionRequest extends FormRequest
{
    /**
     * Allowed parameters in the request.
     */
    private const ALLOWED_PARAMS = ['include', 'filter', 'order', 'limit', 'page'];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * Check if there are any unexpected parameters in the request.
     * If there are, throw a validation exception.
     */
    protected function prepareForValidation(): void
    {
        $extraParams = array_diff(array_keys($this->all()), self::ALLOWED_PARAMS);

        if (! empty($extraParams)) {
            throw ValidationException::withMessages([
                'error' => ['Unexpected parameters: '.implode(', ', $extraParams)],
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * At least one of the following parameters must be present: include, filter, order, limit, page
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'include' => 'string',
            'filter' => 'string',
            'order' => 'string',
            'limit' => 'integer',
            'page' => 'integer',
        ];
    }
}
