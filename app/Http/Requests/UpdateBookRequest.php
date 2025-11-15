<?php

namespace App\Http\Requests;

use App\Triats\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize(): bool
    {
        return auth()->user()->hasRole('author');
    }

    public function rules(): array
    {
        // same as store but all optional
        return [
            'title_en' => 'sometimes|string|max:255',
            'title_ar' => 'sometimes|string|max:255',
            'description_en' => 'sometimes|string',
            'description_ar' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('validation.title_en_required'),
            'title_ar.required' => __('validation.title_ar_required'),
            'description_en.required' => __('validation.description_en_required'),
            'description_ar.required' => __('validation.description_ar_required'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(
                $validator->errors()->first(),
                422
            )
        );
    }
}
