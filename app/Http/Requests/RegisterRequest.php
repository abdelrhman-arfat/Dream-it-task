<?php

namespace App\Http\Requests;

use App\Triats\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ResponseTrait ;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email|unique:users,email",
            "name_ar" => ["required", "regex:/^[\p{L}\s]+$/u"],
            "name_en" => ["required", "regex:/^[\p{L}\s]+$/u"],
            "password" => "required|min:6|confirmed",
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => __('validation.email_required'),
            "email.email" => __('validation.email_email'),
            "email.unique" => __('validation.email_unique'),

            "name_ar.required" => __('validation.name_required'),
            "name_ar.regex" => __('validation.name_regex'),
            "name_en.required" => __('validation.name_required'),
            "name_en.regex" => __('validation.name_regex'),

            "password.required" => __('validation.password_required'),
            "password.min" => __('validation.password_min'),
            "password.confirmed" => __('validation.password_confirmed'),
        ];
    }


    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            $this->errorResponse(
                $validator->errors()->first(),
                422
            )
        );
    }

}
