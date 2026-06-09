<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'product_code' => '',
        'product_name' => 'required|string|max:255',
        'product_imei' => 'required|numeric',
        'brand_id' => 'required|integer|exists:brands,id',
        'series_id' => 'required|integer|exists:series,id',
        'color_id' => 'required|integer|exists:colors,id',
        'condition' => 'required|string|max:255',
        'storage_id' => 'required|integer|exists:storages,id',
        'model_type_id' => 'required|integer|exists:model_types,id',
        'network_id' => 'required_if:type_of_machine,2',
        'battery_percentage' => '',
        'percentage' => '',
        'purchase_price' => 'required|numeric',
        'selling_price' => 'numeric',
        'employee_id'   => '',
        'purchase_date' => 'required|date',
        'image' => '',
        'status' => 'required|string', // Change 'active' and 'inactive' to the valid status values.
        'note' => '',
        'type_of_machine' => 'required|string|max:255',
    ];
    }
}
