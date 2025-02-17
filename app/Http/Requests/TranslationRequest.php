<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this if you have role-based authorization
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'locale' => ['required', 'string', 'max:5', 'in:en,fr,es,de,it'], // Add more locales if needed
            'key' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'tag' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Customize error messages for validation.
     */
    public function messages(): array
    {
        return [
            'locale.required' => 'The language code is required.',
            'locale.in' => 'Invalid locale provided. Supported: en, fr, es, de, it.',
            'key.required' => 'The translation key is required.',
            'key.max' => 'The translation key must not exceed 255 characters.',
            'content.required' => 'The translation content cannot be empty.',
            'tag.max' => 'The tag should not be longer than 50 characters.',
        ];
    }
}
