<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route("user") ? $this->route("user")->id : null;
        return [
            'name' => ["required", "string", "max:255"],
            'email' => ["required", "email", "unique:users,email,".$userId],
            'password' => [$this->isMethod("post") ? "required" : "nullable","min:8"],
            'id_level' => ["required","exists:levels,id"]
        ];
    }
}
