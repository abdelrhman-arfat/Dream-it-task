<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Triats\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('author');
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'title_en.required' => __('validation.title_en_required'),
            'title_ar.required' => __('validation.title_ar_required'),
            'description_en.required' => __('validation.description_en_required'),
            'description_ar.required' => __('validation.description_ar_required'),
        ];
    }

    /**
     * Handle failed validation
     */
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
