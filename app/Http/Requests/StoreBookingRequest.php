<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return session()->has('customer_id'); // Phải là customer đang đăng nhập
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'service_id' => 'required|exists:services,service_id',
        'booking_date' => 'required|date',
        'booking_time' => 'required',
        'address' => 'required|string|max:255',
        'customer_name' => 'required|string|max:100',
        'customer_phone' => 'required|string|max:20',
        'payment_method' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'booking_date.required' => 'Vui lòng chọn ngày đặt lịch.',
            'booking_date.after_or_equal' => 'Ngày đặt lịch không được trong quá khứ.',
            'booking_time.required' => 'Vui lòng chọn giờ đặt lịch.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
