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
            'resume.required' => 'A résumé file is required.',
            'resume.file' => 'The résumé must be a valid file.',
            'resume.max' => 'The résumé must not exceed :max kilobytes.',
            'resume.mimes' => 'The résumé must be a PDF or DOCX document.',
            'email.email' => 'A valid email address is required.',
        ];
    }
}
