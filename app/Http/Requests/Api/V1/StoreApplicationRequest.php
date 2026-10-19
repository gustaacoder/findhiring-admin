<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $maxKb = (int) config('findhiring.screening.max_upload_size_kb', 5120);

        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'resume' => ['required', 'file', 'max:'.$maxKb, 'mimes:pdf,docx'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.required' => __('Your full name is required.'),
            'name.string' => __('Your full name must be text.'),
            'name.min' => __('Your full name must contain at least :min characters.'),
            'name.max' => __('Your full name must not exceed :max characters.'),
            'email.required' => __('Your email address is required.'),
            'email.string' => __('Your email address must be text.'),
            'email.email' => __('A valid email address is required.'),
            'email.max' => __('Your email address must not exceed :max characters.'),
            'phone.string' => __('Your phone number must be text.'),
            'phone.max' => __('Your phone number must not exceed :max characters.'),
            'resume.required' => __('A résumé file is required.'),
            'resume.file' => __('The résumé must be a valid file.'),
            'resume.max' => __('The résumé must not exceed :max kilobytes.'),
            'resume.mimes' => __('The résumé must be a PDF or DOCX document.'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'email' => __('Email'),
            'phone' => __('Phone'),
            'resume' => __('Résumé'),
        ];
    }
}
