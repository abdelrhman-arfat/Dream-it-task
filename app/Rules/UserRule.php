<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;


class UserRule implements ValidationRule
{
    protected ?User $user = null;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $u = User::where("email", $value)->first();

        if (!$u) {
            $fail(__("validation.user_not_found"));
            return;
        }

        if (!$u->hasRole("author")) {
            $fail(__("validation.user_not_author"));
            return;
        }

        $this->user = $u;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
