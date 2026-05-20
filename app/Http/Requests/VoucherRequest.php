<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
        $voucherId = $this->route("voucher") ? $this->route("voucher")->id : null;
        return [
            'voucher_code' => 'required|unique:vouchers,voucher_code,'.$voucherId,
            'discount_precentage' => 'required|numeric|min:1|max:100',
            'expired_at' => 'required|date',
            'is_active'=> [$this->isMethod("put") ? "required" : "nullable"],
        ];
    }
}
