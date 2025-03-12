<?php

namespace App\Http\Requests;

use App\Enums\TicketCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20', // min:20 en vez de 29
            'category' => 'required|in:' . implode(',', TicketCategory::values()),
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,png,pdf,docx' // comas correctas
        ];
    }

    public function messages(): array
    {
        return [
            'category.in' => 'La categoría seleccionada es inválida',
            'attachments.*.max' => 'Cada archivo no debe exceder 5MB' // 5MB en vez de 5%
        ];
    }
}
