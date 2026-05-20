<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
        return [
            'id_customer' => 'nullable|exists:customers,id',
            'name'        => 'required_without:id_customer|nullable|string|max:255',
            'phone'       => 'required_without:id_customer|nullable|string|max:20',
            'address'     => 'required_without:id_customer|nullable|string',
            'id_service'  => 'required|array',
            'qty'         => 'required|array',
            'voucher_code' => 'nullable|string',
            'payment_method' => 'required|in:now,later',
            'order_pay'      => 'required_if:payment_method,now|nullable|numeric|min:0',
        ];
    }
}
