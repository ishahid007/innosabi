<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'include' => 'required_without_all:filter,order,limit,page',
            'filter' => 'string',
            'order' => 'string',
            'limit' => 'integer',
            'page' => 'integer',
        ];
    }
}
