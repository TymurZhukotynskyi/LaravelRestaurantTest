<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingRequest extends FormRequest
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
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'time_start' => 'required|date_format:H:i|after:08:59|before:time_end',
            'time_end' => 'required|date_format:H:i|before:23:31|after:time_start',
            'guests' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'Booking date must be today or later.',
            'time_start.after' => 'Restaurant opens at 09:00.',
            'time_start.before:time_end' => 'The end time must be after the start time.',
            'time_end.after.time_start' => 'The end time must be after the start time.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
