<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\UserRule;
use App\Triats\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    use ResponseTrait ;

    protected UserRule $userExits;

    protected function prepareForValidation(): void
    {
        $this->userExits = new UserRule();
    }
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
            'email' => ['required', 'email', $this->userExits],
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            // Email
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_email'),
            // Note: UserRule will handle 'user not found' message separately

            // Password
            'password.required' => __('validation.password_required'),
            'password.min' => __('validation.password_min'),
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

    public function getValidatedUser(): ?User
    {
        return $this->userExits->getUser();
    }
}
